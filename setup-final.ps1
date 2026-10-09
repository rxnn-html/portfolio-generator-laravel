# setup-final.ps1 : production fixes + logo + deploy for the Laravel project
$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) {
    $full = Join-Path (Get-Location) $p
    $dir = Split-Path $full -Parent
    if (-not (Test-Path $dir)) { New-Item -ItemType Directory -Force $dir | Out-Null }
    [System.IO.File]::WriteAllText($full, $t.Replace("`r`n", "`n"), $utf8)
}
function Step($t) { Write-Host ""; Write-Host "== $t" -ForegroundColor Cyan }

if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

# ---------------------------------------------------------------- 1. composer.json
Step "1. PostgreSQL extensions in composer.json"
$c = ReadF "composer.json"
if ($c -notmatch 'ext-pdo_pgsql') {
    $rx = [regex]'("require"\s*:\s*\{)'
    $c = $rx.Replace($c, { param($m) $m.Groups[1].Value + "`n        `"ext-pdo_pgsql`": `"*`",`n        `"ext-pgsql`": `"*`"," }, 1)
    SaveF "composer.json" $c
    Write-Host "added ext-pdo_pgsql and ext-pgsql"
    composer update --lock --no-interaction
    if ($LASTEXITCODE -ne 0) { Write-Host "MANUAL: composer update --lock failed. Enable pdo_pgsql and pgsql in Laragon, then run it again." -ForegroundColor Yellow }
} else { Write-Host "already done" }

# ---------------------------------------------------------------- 2. trustProxies
Step "2. Trust Railway's proxy (bootstrap/app.php)"
$b = ReadF "bootstrap/app.php"
if ($b -match 'trustProxies') {
    Write-Host "already done"
} else {
    $rx = [regex]'(withMiddleware\(function[^{]*\{)\s*//\s*'
    if ($rx.IsMatch($b)) {
        $b = $rx.Replace($b, { param($m) $m.Groups[1].Value + "`n        " + '$middleware->trustProxies(at: ''*'');' + "`n    " }, 1)
        SaveF "bootstrap/app.php" $b
        Write-Host "added trustProxies"
    } else {
        Write-Host "MANUAL: put this inside withMiddleware(...) in bootstrap/app.php:  `$middleware->trustProxies(at: '*');" -ForegroundColor Yellow
    }
}

# ---------------------------------------------------------------- 3. force https
Step "3. HTTPS links in production (AppServiceProvider)"
$pf = "app/Providers/AppServiceProvider.php"
$t = ReadF $pf
if ($t -match 'forceScheme') {
    Write-Host "already done"
} else {
    $rx = [regex]'(public function boot\(\)[^{]*\{)\s*//\s*'
    if ($rx.IsMatch($t)) {
        $t = $rx.Replace($t, { param($m) $m.Groups[1].Value + "`n        if (app()->environment('production')) {`n            URL::forceScheme('https');`n        }`n    " }, 1)
        if ($t -notmatch 'Facades\\URL') {
            $t = $t.Replace('namespace App\Providers;', "namespace App\Providers;`n`nuse Illuminate\Support\Facades\URL;")
        }
        SaveF $pf $t
        Write-Host "added forceScheme"
    } else {
        Write-Host "MANUAL: add URL::forceScheme('https') inside the production check in boot() of AppServiceProvider.php" -ForegroundColor Yellow
    }
}

# ---------------------------------------------------------------- 4. .gitignore
Step "4. Let Git include the built CSS/JS (public/build)"
$g = ReadF ".gitignore"
$lines = $g -split "`n"
$kept = $lines | Where-Object { $_.Trim() -ne '/public/build' }
if ($kept.Count -ne $lines.Count) {
    SaveF ".gitignore" ($kept -join "`n")
    Write-Host "removed /public/build from .gitignore"
} else { Write-Host "already done" }

# ---------------------------------------------------------------- 5. buttons
Step "5. Copy link and Print buttons without Alpine"
$sp = "resources/views/portfolios/show.blade.php"
if (Test-Path $sp) {
    $s = ReadF $sp
    $copyRx  = [regex]'(?s)<button type="button" class="\{\{ \$btn \}\}" x-data="\{ copied: false \}".*?</button>'
    $printRx = [regex]'(?s)<button type="button" class="\{\{ \$btn \}\}" x-data="\{ t: .*?</button>'
    $copyNew  = '<button type="button" class="{{ $btn }}" onclick="navigator.clipboard.writeText(location.origin + location.pathname); this.textContent = ''Copied!'';">Copy link</button>'
    $printNew = '<button type="button" class="{{ $btn }}" onclick="window.print()">Print / Save as PDF</button>'
    $changed = $false
    if ($copyRx.IsMatch($s))  { $s = $copyRx.Replace($s,  { param($m) $copyNew }, 1);  $changed = $true }
    if ($printRx.IsMatch($s)) { $s = $printRx.Replace($s, { param($m) $printNew }, 1); $changed = $true }
    if ($changed) { SaveF $sp $s; Write-Host "buttons replaced" } else { Write-Host "already done" }
} else { Write-Host "MANUAL: show.blade.php not found" -ForegroundColor Yellow }

# ---------------------------------------------------------------- 6. logo
Step "6. Logo and favicon"
SaveF "public/favicon.svg" @'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" role="img" aria-label="Portfolio Generator">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#4f46e5"/>
      <stop offset="1" stop-color="#d946ef"/>
    </linearGradient>
  </defs>
  <rect width="64" height="64" rx="16" fill="url(#g)"/>
  <path d="M24 50V14H37a11 11 0 0 1 0 22H24" fill="none" stroke="#fff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
  <circle cx="46" cy="47" r="5" fill="#fcd34d"/>
</svg>
'@
Write-Host "created public/favicon.svg"

# PNG and ICO versions (optional: needs Windows System.Drawing)
$havePng = $false
try {
    Add-Type -AssemblyName System.Drawing
    function New-Logo([int]$size) {
        $bmp = New-Object System.Drawing.Bitmap $size, $size
        $g = [System.Drawing.Graphics]::FromImage($bmp)
        $g.SmoothingMode = 'AntiAlias'
        $g.Clear([System.Drawing.Color]::Transparent)
        $d = [single]($size * 0.5)
        $round = New-Object System.Drawing.Drawing2D.GraphicsPath
        $round.AddArc([single]0, [single]0, $d, $d, 180, 90)
        $round.AddArc([single]($size - $d), [single]0, $d, $d, 270, 90)
        $round.AddArc([single]($size - $d), [single]($size - $d), $d, $d, 0, 90)
        $round.AddArc([single]0, [single]($size - $d), $d, $d, 90, 90)
        $round.CloseFigure()
        $brush = [System.Drawing.Drawing2D.LinearGradientBrush]::new(
            [System.Drawing.PointF]::new(0, 0), [System.Drawing.PointF]::new($size, $size),
            [System.Drawing.ColorTranslator]::FromHtml('#4f46e5'), [System.Drawing.ColorTranslator]::FromHtml('#d946ef'))
        $g.FillPath($brush, $round)
        $k = $size / 64.0
        $pen = [System.Drawing.Pen]::new([System.Drawing.Color]::White, [single](8 * $k))
        $pen.StartCap = 'Round'; $pen.EndCap = 'Round'; $pen.LineJoin = 'Round'
        $p = New-Object System.Drawing.Drawing2D.GraphicsPath
        $p.AddLine([single](24*$k), [single](50*$k), [single](24*$k), [single](14*$k))
        $p.AddLine([single](24*$k), [single](14*$k), [single](37*$k), [single](14*$k))
        $p.AddArc([single](26*$k), [single](14*$k), [single](22*$k), [single](22*$k), -90, 180)
        $p.AddLine([single](37*$k), [single](36*$k), [single](24*$k), [single](36*$k))
        $g.DrawPath($pen, $p)
        $dot = [System.Drawing.SolidBrush]::new([System.Drawing.ColorTranslator]::FromHtml('#fcd34d'))
        $g.FillEllipse($dot, [single](41*$k), [single](42*$k), [single](10*$k), [single](10*$k))
        $g.Dispose()
        return $bmp
    }
    $pub = Join-Path (Get-Location) "public"
    $a = New-Logo 180; $a.Save((Join-Path $pub "apple-touch-icon.png"), [System.Drawing.Imaging.ImageFormat]::Png); $a.Dispose()
    $b32 = New-Logo 32; $b32.Save((Join-Path $pub "favicon-32.png"), [System.Drawing.Imaging.ImageFormat]::Png)
    $ico = [System.Drawing.Icon]::FromHandle($b32.GetHicon())
    $fs = [System.IO.File]::Create((Join-Path $pub "favicon.ico")); $ico.Save($fs); $fs.Close(); $b32.Dispose()
    $havePng = $true
    Write-Host "created apple-touch-icon.png, favicon-32.png, favicon.ico"
} catch {
    Write-Host "PNG/ICO skipped (the SVG icon still works in all modern browsers)." -ForegroundColor Yellow
}

# link the icons in the layout
$lp = "resources/views/layouts/app.blade.php"
$l = ReadF $lp
if ($l -notmatch 'favicon\.svg') {
    $tags = '    <link rel="icon" type="image/svg+xml" href="{{ asset(''favicon.svg'') }}">' + "`n"
    if ($havePng) {
        $tags += '    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(''favicon-32.png'') }}">' + "`n"
        $tags += '    <link rel="apple-touch-icon" href="{{ asset(''apple-touch-icon.png'') }}">' + "`n"
    }
    $tags += '    <meta name="theme-color" content="#4f46e5">' + "`n"
    if ($l.Contains('    @vite(')) {
        $l = $l.Replace('    @vite(', $tags + '    @vite(')
        SaveF $lp $l
        Write-Host "icons linked in layouts/app.blade.php"
    } else { Write-Host "MANUAL: add the favicon <link> tags inside <head> of layouts/app.blade.php" -ForegroundColor Yellow }
} else { Write-Host "layout already links the icons" }

# use the logo in the navbar
$np = "resources/views/partials/navbar.blade.php"
if (Test-Path $np) {
    $n = ReadF $np
    $oldLogo = '<span class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-indigo-600 to-fuchsia-500 text-sm font-extrabold text-white">P</span>'
    $newLogo = '<img src="{{ asset(''favicon.svg'') }}" alt="" class="h-8 w-8">'
    if ($n.Contains($oldLogo)) { SaveF $np $n.Replace($oldLogo, $newLogo); Write-Host "navbar now uses the logo" } else { Write-Host "navbar already updated (or customised)" }
}

# ---------------------------------------------------------------- 7. build + push
Step "7. Build the assets"
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "The build failed. Fix the error above, then run this script again." -ForegroundColor Red; return }

Step "8. Safety check, commit and push"
$tracked = git ls-files
if ($tracked -contains '.env') {
    Write-Host "STOP: .env is tracked by Git. Run: git rm --cached .env   then run this script again." -ForegroundColor Red
    return
}
git add .
git commit -m "Production settings, logo, working print button"
$remote = git remote get-url origin 2>$null
if ($remote) { git push -u origin main } else { Write-Host "MANUAL: no GitHub remote yet. Run: git remote add origin https://github.com/YOUR-USERNAME/portfolio-laravel.git  then: git push -u origin main" -ForegroundColor Yellow }

Write-Host ""
Write-Host "ALL DONE."