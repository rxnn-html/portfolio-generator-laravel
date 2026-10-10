$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) { [System.IO.File]::WriteAllText((Join-Path (Get-Location) $p), $t.Replace("`r`n", "`n"), $utf8) }
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

$sp = "resources/views/portfolios/show.blade.php"
if (-not (Test-Path $sp)) { Write-Host "show.blade.php not found" -ForegroundColor Red; return }
$s = ReadF $sp

# 1. one filled button style for all three action buttons
$filled = 'inline-flex min-h-11 cursor-pointer items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-500 hover:shadow-md hover:shadow-indigo-500/30 active:translate-y-0'
$btnRx = [regex]::new('\$btn = ''[^'']*'';')
if ($btnRx.IsMatch($s)) {
    $s = $btnRx.Replace($s, { param($m) '$btn = ''' + $filled + ''';' }, 1)
    Write-Host "button style updated"
} else {
    Write-Host "MANUAL: could not find the `$btn line in show.blade.php" -ForegroundColor Yellow
}

# 2. Edit Portfolio uses the same style, so all three match
$editRx = [regex]::new('(?s)<a href="\{\{ route\(''portfolios\.edit'', \$portfolio\) \}\}"\s+class="[^"]*">Edit Portfolio</a>')
if ($editRx.IsMatch($s)) {
    $s = $editRx.Replace($s, { param($m) '<a href="{{ route(''portfolios.edit'', $portfolio) }}" class="{{ $btn }}">Edit Portfolio</a>' }, 1)
    Write-Host "Edit Portfolio now uses the same style"
}

# 3. readable template badge in dark mode
$badgeOld = 'bg-indigo-100 px-2.5 py-0.5 text-xs font-medium capitalize text-indigo-700'
$badgeNew = 'bg-indigo-500/20 px-2.5 py-0.5 text-xs font-medium capitalize text-indigo-700 dark:text-indigo-200'
if ($s.Contains($badgeOld)) { $s = $s.Replace($badgeOld, $badgeNew); Write-Host "template badge contrast fixed" }

SaveF $sp $s

php artisan view:clear
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "The build failed. Send me the first red line." -ForegroundColor Red; return }

$tracked = git ls-files
if ($tracked -contains '.env') { Write-Host "STOP: .env is tracked by Git. Run: git rm --cached .env  then run this script again." -ForegroundColor Red; return }
git add .
git commit -m "Make Copy link and Print buttons look clickable"
git push

Write-Host ""
Write-Host "ALL DONE."