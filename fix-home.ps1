$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) { [System.IO.File]::WriteAllText((Join-Path (Get-Location) $p), $t.Replace("`r`n", "`n"), $utf8) }
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

# 1. remove the scroll-reveal script (it could leave sections invisible)
$js = ReadF "resources/js/app.js"
$js2 = [regex]::Replace($js, '(?s)\n*// Reveal sections as they scroll.*$', "`n")
if ($js2 -ne $js) { SaveF "resources/js/app.js" $js2; Write-Host "scroll-reveal script removed" } else { Write-Host "scroll-reveal script already gone" }

# 2. fix the Home page
$hp = "resources/views/home.blade.php"
$h = ReadF $hp
$h = $h.Replace(' data-reveal-group', '')

# the hero keeps its own dark colours (dark-mode rules must not touch it)
if ($h -notmatch 'theme-fixed relative isolate') {
    $h = $h.Replace('<section class="relative isolate overflow-hidden rounded-[2rem] bg-slate-950', '<section class="theme-fixed relative isolate overflow-hidden rounded-[2rem] bg-slate-950')
}

# the invisible button: white-on-dark became dark-on-dark
$h = $h.Replace('rounded-xl bg-white px-7 text-base font-semibold text-slate-900 transition hover:-translate-y-0.5 hover:bg-slate-100',
                'rounded-xl bg-slate-50 px-7 text-base font-semibold text-slate-900 transition hover:-translate-y-0.5 hover:bg-white')
SaveF $hp $h
Write-Host "home.blade.php fixed"

# 3. make sure the "How it works" section really exists
if ($h -notmatch 'id="how-it-works"') {
    Write-Host "WARNING: the How it works section is missing from home.blade.php. Tell me and I will send it again." -ForegroundColor Yellow
} else { Write-Host "How it works section found" }

php artisan view:clear
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "The build failed. Send me the first red line." -ForegroundColor Red; return }

$tracked = git ls-files
if ($tracked -contains '.env') { Write-Host "STOP: .env is tracked by Git. Run: git rm --cached .env  then run this script again." -ForegroundColor Red; return }
git add .
git commit -m "Fix home page: remove scroll reveal, fix invisible button"
git push

Write-Host ""
Write-Host "ALL DONE."