param([switch]$Init, [switch]$Once)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
$catalogPath = Join-Path $root 'includes\i18n.php'
$statePath = Join-Path $root '.translation-sync-state.json'
$languages = @('fr', 'en', 'de')
$utf8 = New-Object System.Text.UTF8Encoding($false)

function Write-Utf8File([string]$Path, [string]$Content) {
    [System.IO.File]::WriteAllText($Path, $Content, $utf8)
}

function Get-PhpCatalog([string]$Content) {
    $markers = [regex]::Matches($Content, '(?m)^\s*''(fr|en|de)''\s*=>\s*\[')
    $catalog = @{}
    for ($index = 0; $index -lt $markers.Count; $index++) {
        $start = $markers[$index].Index
        $end = if ($index + 1 -lt $markers.Count) { $markers[$index + 1].Index } else { $Content.Length }
        $block = $Content.Substring($start, $end - $start)
        $entries = @{}
        foreach ($entry in [regex]::Matches($block, "\'(?<key>[^\'\r\n]+)\'\s*=>\s*\'(?<value>(?:\\.|[^\'\\])*)\'")) {
            $value = $entry.Groups['value'].Value.Replace('\\', '\').Replace("\'", "'")
            $entries[$entry.Groups['key'].Value] = $value
        }
        $catalog[$markers[$index].Groups[1].Value] = $entries
    }
    foreach ($language in $languages) {
        if (-not $catalog.ContainsKey($language)) {
            throw "Le dictionnaire $language est introuvable dans includes/i18n.php."
        }
    }
    return $catalog
}

function Get-TextHash([string]$Text) {
    $bytes = [System.Text.Encoding]::UTF8.GetBytes($Text)
    $sha = [System.Security.Cryptography.SHA256]::Create()
    try {
        return [System.BitConverter]::ToString($sha.ComputeHash($bytes)).Replace('-', '').ToLowerInvariant()
    } finally {
        $sha.Dispose()
    }
}

function Get-CatalogHashes($Catalog) {
    $hashes = @{}
    $keys = @($languages | ForEach-Object { $Catalog[$_].Keys }) | Select-Object -Unique
    foreach ($key in $keys) {
        $hashes[$key] = @{}
        foreach ($language in $languages) {
            if ($Catalog[$language].ContainsKey($key)) {
                $hashes[$key][$language] = Get-TextHash ([string]$Catalog[$language][$key])
            }
        }
    }
    return $hashes
}

function Read-TranslationState {
    if (-not (Test-Path -LiteralPath $statePath)) { return $null }
    try {
        $json = Get-Content -LiteralPath $statePath -Raw -Encoding UTF8 | ConvertFrom-Json
        if ($json.version -ne 1 -or $null -eq $json.hashes) { throw 'Format de fichier d’état inconnu.' }
        $hashes = @{}
        foreach ($property in $json.hashes.PSObject.Properties) {
            $entry = @{}
            foreach ($language in $languages) {
                $value = $property.Value.PSObject.Properties[$language]
                if ($null -ne $value) { $entry[$language] = [string]$value.Value }
            }
            $hashes[$property.Name] = $entry
        }
        return @{ version = 1; hashes = $hashes }
    } catch {
        throw "Impossible de lire .translation-sync-state.json : $($_.Exception.Message)"
    }
}

function Save-TranslationState($Catalog, $PreviousState) {
    $hashes = Get-CatalogHashes $Catalog
    if ($null -ne $PreviousState) {
        foreach ($key in $PreviousState.hashes.Keys) {
            if (-not $hashes.ContainsKey($key)) { $hashes[$key] = $PreviousState.hashes[$key] }
        }
    }
    $state = @{ version = 1; hashes = $hashes } | ConvertTo-Json -Depth 8
    Write-Utf8File $statePath ($state + [Environment]::NewLine)
    return @{ version = 1; hashes = $hashes }
}

function Get-DeepLKey {
    if (-not [string]::IsNullOrWhiteSpace($env:DEEPL_AUTH_KEY)) { return $env:DEEPL_AUTH_KEY.Trim() }
    foreach ($filename in @('.env.local', '.env')) {
        $filePath = Join-Path $root $filename
        if (-not (Test-Path -LiteralPath $filePath)) { continue }
        foreach ($line in (Get-Content -LiteralPath $filePath)) {
            if ($line -match '^\s*(?:export\s+)?DEEPL_AUTH_KEY\s*=\s*(.*?)\s*$') {
                $value = $Matches[1].Trim()
                if ($value.Length -ge 2 -and (($value[0] -eq '"' -and $value[$value.Length - 1] -eq '"') -or ($value[0] -eq "'" -and $value[$value.Length - 1] -eq "'"))) {
                    $value = $value.Substring(1, $value.Length - 2)
                }
                return $value
            }
        }
    }
    return ''
}

function Invoke-DeepLBatch([string[]]$Texts, [string]$SourceLanguage, [string]$TargetLanguage, [string]$ApiKey) {
    $endpoint = if ($ApiKey.EndsWith(':fx')) { 'https://api-free.deepl.com/v2/translate' } else { 'https://api.deepl.com/v2/translate' }
    $form = "source_lang=$($SourceLanguage.ToUpperInvariant())&target_lang=$($TargetLanguage.ToUpperInvariant())&preserve_formatting=1"
    foreach ($text in $Texts) {
        $form += '&text=' + [System.Net.WebUtility]::UrlEncode($text)
    }
    $response = Invoke-RestMethod -Method Post -Uri $endpoint -Headers @{ Authorization = "DeepL-Auth-Key $ApiKey" } -ContentType 'application/x-www-form-urlencoded' -Body $form -TimeoutSec 20
    if ($null -eq $response.translations -or $response.translations.Count -ne $Texts.Count) {
        throw "DeepL a renvoyé des traductions incomplètes ($SourceLanguage → $TargetLanguage)."
    }
    return @($response.translations | ForEach-Object { [string]$_.text })
}

function Set-PhpCatalogText([string]$Content, [string]$Language, [string]$Key, [string]$Value) {
    $marker = [regex]::Match($Content, "(?m)^\s*'$Language'\s*=>\s*\[")
    if (-not $marker.Success) { throw "Le dictionnaire $Language est introuvable." }
    $nextMarker = [regex]::Match($Content.Substring($marker.Index + $marker.Length), "(?m)^\s*'(?:fr|en|de)'\s*=>\s*\[")
    $end = if ($nextMarker.Success) { $marker.Index + $marker.Length + $nextMarker.Index } else { $Content.Length }
    $block = $Content.Substring($marker.Index, $end - $marker.Index)
    $escapedKey = [regex]::Escape($Key)
    $pattern = "(?m)(^\s*'$escapedKey'\s*=>\s*)'(?:\\.|[^'\\])*'"
    $entry = [regex]::Match($block, $pattern)
    if (-not $entry.Success) { throw "La clé '$Key' est absente du dictionnaire $Language." }
    $escapedValue = $Value.Replace('\', '\\').Replace("'", "\'")
    $updatedBlock = [regex]::Replace($block, $pattern, [System.Text.RegularExpressions.MatchEvaluator]{
        param($match)
        return $match.Groups[1].Value + "'" + $escapedValue + "'"
    }, 1)
    return $Content.Substring(0, $marker.Index) + $updatedBlock + $Content.Substring($end)
}

function Sync-Translations {
    $sourceContent = [System.IO.File]::ReadAllText($catalogPath)
    $catalog = Get-PhpCatalog $sourceContent
    $state = Read-TranslationState
    if ($null -eq $state) {
        Save-TranslationState $catalog $null | Out-Null
        Write-Output 'État initial des traductions créé ; les prochaines modifications seront synchronisées.'
        return
    }

    $changedByKey = @{}
    $keys = @($languages | ForEach-Object { $catalog[$_].Keys }) | Select-Object -Unique
    foreach ($key in $keys) {
        $changedLanguages = @()
        foreach ($language in $languages) {
            if (-not $catalog[$language].ContainsKey($key) -or -not $state.hashes.ContainsKey($key)) { continue }
            if (-not $state.hashes[$key].ContainsKey($language)) { continue }
            if ((Get-TextHash ([string]$catalog[$language][$key])) -ne $state.hashes[$key][$language]) {
                $changedLanguages += $language
            }
        }
        if ($changedLanguages.Count -gt 1) {
            Write-Warning "Synchronisation ignorée pour '$key' : plusieurs langues ont changé. Modifiez une langue à la fois."
        } elseif ($changedLanguages.Count -eq 1) {
            $changedByKey[$key] = $changedLanguages[0]
        }
    }
    if ($changedByKey.Count -eq 0) { return }

    $apiKey = Get-DeepLKey
    if ([string]::IsNullOrWhiteSpace($apiKey)) {
        Write-Warning 'Phrases modifiées détectées, mais DEEPL_AUTH_KEY manque. Configurez-la dans .env.local puis enregistrez de nouveau i18n.php.'
        return
    }

    $requests = @{}
    foreach ($key in @($changedByKey.Keys)) {
        $missingLanguages = @($languages | Where-Object { -not $catalog[$_].ContainsKey($key) })
        if ($missingLanguages.Count -gt 0) {
            Write-Warning "Synchronisation ignorée pour '$key' : sa clé doit exister dans les trois dictionnaires."
            $changedByKey.Remove($key)
            continue
        }
        $sourceLanguage = $changedByKey[$key]
        foreach ($targetLanguage in ($languages | Where-Object { $_ -ne $sourceLanguage })) {
            $requestKey = "$sourceLanguage`:$targetLanguage"
            if (-not $requests.ContainsKey($requestKey)) { $requests[$requestKey] = [System.Collections.Generic.List[string]]::new() }
            $requests[$requestKey].Add($key)
        }
    }
    if ($changedByKey.Count -eq 0) { return }

    $translated = @{}
    foreach ($key in $changedByKey.Keys) { $translated[$key] = @{} }
    $failed = @{}
    foreach ($requestKey in $requests.Keys) {
        $parts = $requestKey.Split(':')
        $sourceLanguage = $parts[0]
        $targetLanguage = $parts[1]
        $requestKeys = @($requests[$requestKey])
        for ($offset = 0; $offset -lt $requestKeys.Count; $offset += 40) {
            $last = [Math]::Min($offset + 39, $requestKeys.Count - 1)
            $batchKeys = @($requestKeys[$offset..$last])
            $texts = @($batchKeys | ForEach-Object { [string]$catalog[$sourceLanguage][$_] })
            try {
                $results = Invoke-DeepLBatch $texts $sourceLanguage $targetLanguage $apiKey
                for ($index = 0; $index -lt $batchKeys.Count; $index++) {
                    $translated[$batchKeys[$index]][$targetLanguage] = $results[$index]
                }
            } catch {
                foreach ($key in $batchKeys) { $failed[$key] = $true }
                Write-Warning "DeepL : $($_.Exception.Message)"
            }
        }
    }

    $updatedContent = $sourceContent
    $completed = @()
    foreach ($key in $changedByKey.Keys) {
        $sourceLanguage = $changedByKey[$key]
        $targets = @($languages | Where-Object { $_ -ne $sourceLanguage })
        if ($failed.ContainsKey($key) -or @($targets | Where-Object { -not $translated[$key].ContainsKey($_) }).Count -gt 0) { continue }
        foreach ($targetLanguage in $targets) {
            $updatedContent = Set-PhpCatalogText $updatedContent $targetLanguage $key $translated[$key][$targetLanguage]
        }
        $completed += $key
    }
    if ($completed.Count -eq 0) {
        Write-Warning 'Aucune traduction complète à enregistrer ; les dictionnaires n’ont pas été modifiés.'
        return
    }

    $temporaryPath = "$catalogPath.translation.tmp"
    try {
        Write-Utf8File $temporaryPath $updatedContent
        Move-Item -LiteralPath $temporaryPath -Destination $catalogPath -Force
        $updatedCatalog = Get-PhpCatalog $updatedContent
        $nextState = @{ version = 1; hashes = $state.hashes }
        foreach ($key in $completed) {
            $nextState.hashes[$key] = @{}
            foreach ($language in $languages) {
                $nextState.hashes[$key][$language] = Get-TextHash ([string]$updatedCatalog[$language][$key])
            }
        }
        Write-Utf8File $statePath (($nextState | ConvertTo-Json -Depth 8) + [Environment]::NewLine)
        Write-Output "Traductions synchronisées directement dans includes/i18n.php : $($completed.Count) phrase(s)."
    } catch {
        if (Test-Path -LiteralPath $temporaryPath) { Remove-Item -LiteralPath $temporaryPath -Force }
        throw
    }
}

if ($Init) {
    $catalog = Get-PhpCatalog ([System.IO.File]::ReadAllText($catalogPath))
    Save-TranslationState $catalog $null | Out-Null
    Write-Output 'État initial enregistré dans .translation-sync-state.json.'
    exit 0
}

if ($Once) {
    Sync-Translations
    exit 0
}

Write-Output 'Translation watcher started'
try {
    Sync-Translations
    $watcher = New-Object System.IO.FileSystemWatcher
    $watcher.Path = Split-Path -Parent $catalogPath
    $watcher.Filter = Split-Path -Leaf $catalogPath
    $watcher.NotifyFilter = [IO.NotifyFilters]::LastWrite -bor [IO.NotifyFilters]::Size -bor [IO.NotifyFilters]::FileName
    $watcher.EnableRaisingEvents = $true
    while ($true) {
        $change = $watcher.WaitForChanged([IO.WatcherChangeTypes]::All, 1000)
        if (-not $change.TimedOut) {
            Start-Sleep -Milliseconds 700
            while (-not $watcher.WaitForChanged([IO.WatcherChangeTypes]::All, 200).TimedOut) {
                Start-Sleep -Milliseconds 200
            }
            try { Sync-Translations } catch { Write-Warning "Erreur du synchroniseur : $($_.Exception.Message)" }
        }
    }
} finally {
    if ($null -ne $watcher) { $watcher.Dispose() }
    Write-Output 'Translation watcher stopped'
}
