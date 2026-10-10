$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) { [System.IO.File]::WriteAllText((Join-Path (Get-Location) $p), $t.Replace("`r`n", "`n"), $utf8) }
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

$hp = "resources/views/home.blade.php"
$h = ReadF $hp

# ============================================================ FINAL BANNER: type your name
$cta = @'
{{-- ================= FINAL CTA ================= --}}
    <section class="theme-fixed relative isolate overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-16 text-white sm:px-12 sm:py-24"
             x-data="{ name: '',
                       get shown() { return this.name.trim() || 'Your Name'; },
                       get initials() { return this.name.trim().split(/\s+/).filter(Boolean).slice(0, 2).map(w => w[0].toUpperCase()).join('') || 'YN'; },
                       get first() { return this.name.trim().split(/\s+/)[0] || ''; },
                       get url() { return '{{ url('/create') }}' + (this.name.trim() ? '?name=' + encodeURIComponent(this.name.trim().slice(0, 100)) : ''); } }">

        <div aria-hidden="true" class="pointer-events-none absolute -left-20 -top-20 -z-10 h-96 w-96 rounded-full bg-indigo-600/40 blur-[110px] motion-safe:animate-drift"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 right-0 -z-10 h-96 w-96 rounded-full bg-fuchsia-600/30 blur-[110px] motion-safe:animate-drift-slow"></div>

        <div class="relative grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">Ready when you are</p>
                <h2 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">
                    Ready to build <span class="bg-linear-to-r from-cyan-300 via-sky-300 to-fuchsia-300 bg-clip-text text-transparent">yours?</span>
                </h2>
                <p class="mt-5 max-w-md text-lg text-slate-300">Type your name and watch your portfolio card come alive. We'll carry it into the form for you.</p>

                <label for="cta-name" class="mt-8 block text-sm font-medium text-slate-300">Your name</label>
                <input id="cta-name" type="text" x-model="name" maxlength="60" autocomplete="name" placeholder="e.g. Alex Rivera"
                       class="mt-2 w-full max-w-md rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-base text-white placeholder-slate-400 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">

                <a :href="url"
                   class="mt-5 inline-flex min-h-12 items-center justify-center rounded-xl bg-slate-50 px-7 text-base font-semibold text-slate-900 shadow-lg transition hover:-translate-y-0.5 hover:bg-white">
                    Create Portfolio<span x-show="first">&nbsp;as&nbsp;<span x-text="first"></span></span>&nbsp;&rarr;
                </a>
            </div>

            {{-- the live card, with a spinning gradient border --}}
            <div class="relative mx-auto w-full max-w-sm" aria-hidden="true">
                <div class="relative overflow-hidden rounded-3xl p-px">
                    <div class="absolute -inset-[100%] bg-[conic-gradient(from_0deg,#22d3ee,#6366f1,#d946ef,#fcd34d,#22d3ee)] motion-safe:animate-[spin_8s_linear_infinite]"></div>
                    <div class="relative rounded-[calc(1.5rem-1px)] bg-slate-900 p-7">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-cyan-400 to-fuchsia-500 text-xl font-extrabold text-slate-950" x-text="initials"></div>
                            <div class="min-w-0">
                                <p class="truncate text-2xl font-extrabold" x-text="shown"></p>
                                <p class="flex items-center gap-1.5 text-xs text-emerald-300"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Open to opportunities</p>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200">PHP</span>
                            <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200">Laravel</span>
                            <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200">SQL</span>
                            <span class="rounded-full bg-fuchsia-500/20 px-3 py-1 text-xs font-semibold text-fuchsia-200">UI / UX</span>
                        </div>
                        <div class="mt-6 space-y-2">
                            <div class="h-2 w-full rounded bg-white/10"></div>
                            <div class="h-2 w-5/6 rounded bg-white/10"></div>
                            <div class="h-2 w-2/3 rounded bg-white/10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
'@
$ctaRx = [regex]::new('(?s)\{\{-- =+ FINAL CTA =+ --\}\}.*?(?=</div>\s*@endsection)')
if ($h -match 'x-model="name"') { Write-Host "banner: already upgraded" }
elseif ($ctaRx.IsMatch($h)) { $h = $ctaRx.Replace($h, { param($m) $cta + "`n" }, 1); Write-Host "banner: upgraded" }
else { Write-Host "MANUAL: could not find the FINAL CTA section in home.blade.php" -ForegroundColor Yellow }
SaveF $hp $h

# ============================================================ pre-fill the name in the Create form
$fp = "resources/views/portfolios/form.blade.php"
if (Test-Path $fp) {
    $f = ReadF $fp
    $old = "old('full_name', `$portfolio?->full_name ?? '')"
    $new = "old('full_name', `$portfolio?->full_name ?? \Illuminate\Support\Str::limit(is_string(request('name')) ? request('name') : '', 100, ''))"
    if ($f.Contains($old)) { SaveF $fp $f.Replace($old, $new); Write-Host "form: name pre-fill added" }
    elseif ($f.Contains('request(''name'')')) { Write-Host "form: pre-fill already added" }
    else { Write-Host "MANUAL: could not patch the form. The banner still works, the name just will not be pre-filled." -ForegroundColor Yellow }
}

php artisan view:clear
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "The build failed. Send me the first red line." -ForegroundColor Red; return }

$tracked = git ls-files
if ($tracked -contains '.env') { Write-Host "STOP: .env is tracked by Git. Run: git rm --cached .env  then run this script again." -ForegroundColor Red; return }
git add .
git commit -m "Home: scroll journey, chat FAQ, live-name banner"
git push

Write-Host ""
Write-Host "ALL DONE."