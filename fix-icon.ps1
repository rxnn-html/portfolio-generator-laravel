$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) {
    $full = Join-Path (Get-Location) $p
    [System.IO.File]::WriteAllText($full, $t.Replace("`r`n", "`n"), $utf8)
}
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

# 1. the logo (SVG works in all modern browsers)
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
Write-Host "public/favicon.svg written"

# 2. Laravel ships an EMPTY favicon.ico. An empty file shows as a globe, so remove it.
$ico = "public/favicon.ico"
if ((Test-Path $ico) -and ((Get-Item $ico).Length -eq 0)) { Remove-Item $ico; Write-Host "removed the empty favicon.ico" }

# 3. link the icons in the layout (replaces any older icon tags)
$lp = "resources/views/layouts/app.blade.php"
$l = ReadF $lp
$l = [regex]::Replace($l, '(?m)^[ \t]*<link rel="(icon|shortcut icon|apple-touch-icon)"[^\n]*\n', '')
$l = [regex]::Replace($l, '(?m)^[ \t]*<meta name="theme-color"[^\n]*\n', '')
$tags = '    <link rel="icon" type="image/svg+xml" href="{{ asset(''favicon.svg'') }}?v=2">' + "`n"
if (Test-Path "public/favicon-32.png")       { $tags += '    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(''favicon-32.png'') }}?v=2">' + "`n" }
if (Test-Path "public/apple-touch-icon.png") { $tags += '    <link rel="apple-touch-icon" href="{{ asset(''apple-touch-icon.png'') }}?v=2">' + "`n" }
$tags += '    <meta name="theme-color" content="#4f46e5">' + "`n"
if ($l.Contains('    @vite(')) {
    $l = $l.Replace('    @vite(', $tags + '    @vite(')
    SaveF $lp $l
    Write-Host "icons linked in the layout (every page extends it)"
} else {
    Write-Host "MANUAL: put these inside <head> of layouts/app.blade.php:" -ForegroundColor Yellow
    Write-Host $tags
}

# 4. navbar logo
$np = "resources/views/partials/navbar.blade.php"
$n = ReadF $np
$old = '<span class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-indigo-600 to-fuchsia-500 text-sm font-extrabold text-white">P</span>'
$new = '<img src="{{ asset(''favicon.svg'') }}?v=2" alt="" class="h-8 w-8">'
if ($n.Contains($old)) { SaveF $np $n.Replace($old, $new); Write-Host "navbar logo updated" } else { Write-Host "navbar already updated (or customised)" }

# 5. commit and push
php artisan view:clear
git add .
git commit -m "Fix favicon on every page"
git push
Write-Host "ALL DONE."