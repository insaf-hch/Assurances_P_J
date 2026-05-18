<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الوثائق المُنتجة — نظام التأمينات</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
:root {
    --bg:         #ffffff;
    --surface:    #FFFFFF;
    --surface2:   #ffffff;
    --border:     #E4D9CE;
    --border2:    #CDBFB0;
    --text:       #000000;
    --text2:      #000000;
    --text3:      #000000;
    --brown:      #7B4F2C;
    --brown2:     #9C6B4A;
    --brown-soft: #F5ECE3;
    --brown-light:#E8D5C2;
    --sky:        #76c4d8;
    --sky2:       #38BDF8;
    --sky-soft:   #E0F6FD;
    --sky-dark:   #0369A1;
    --success:      #5b8061;
    --success-soft: #E8F5EC;
    --warning:      #B45309;
    --warning-soft: #FEF3C7;
    --error:        #B91C1C;
    --error-soft:   #FEE2E2;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'IBM Plex Sans Arabic', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; direction: rtl; }
.layout { display: flex; min-height: 100vh; }

/* ─── SIDEBAR ─────────────────────────────────────────────── */
.sidebar {
    width: 248px;
    background: linear-gradient(175deg, #4A2510 0%, #7B4F2C 55%, #9C6B4A 100%);
    display: flex; flex-direction: column;
    position: sticky; top: 0; height: 100vh; flex-shrink: 0;
    box-shadow: 2px 0 18px rgba(75,37,16,0.18);
}
.sidebar-logo {
    padding: 1.3rem 1.4rem;
    border-bottom: 1px solid rgba(255,255,255,0.13);
    display: flex; align-items: center; gap: 0.75rem;
}
.logo-icon {
    width: 38px; height: 38px;
    background: var(--sky);
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(14,165,201,0.35);
}
.logo-text { font-size: 0.875rem; font-weight: 700; color: #fff; line-height: 1.25; }
.logo-sub  { font-size: 0.65rem; color: rgba(255,255,255,0.55); font-weight: 400; }
.sidebar-nav { padding: 0.75rem 0; flex: 1; }
.nav-label {
    font-size: 0.6rem; font-weight: 700;
    color: rgba(255,255,255,0.38);
    letter-spacing: 0.13em;
    padding: 0.8rem 1.3rem 0.35rem;
    text-transform: uppercase;
}
.nav-item {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.62rem 1.3rem;
    color: rgba(255,255,255,0.68);
    font-size: 0.83rem; font-weight: 400;
    cursor: pointer; transition: all 0.13s;
    border-right: 3px solid transparent;
    text-decoration: none; margin: 1px 0;
}
.nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
.nav-item.active {
    font-weight: 600;
    background: rgba(14,165,201,0.2);
    color: #fff;
    border-right-color: var(--sky2);
}
.nav-icon { font-size: 1rem; width: 20px; text-align: center; }
.sidebar-footer {
    padding: 1rem 1.3rem;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 0.68rem; color: rgba(255,255,255,0.35);
}

/* ─── MAIN ────────────────────────────────────────────────── */
.main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }
.topbar {
    background: var(--surface);
    border-bottom: 2px solid var(--brown-light);
    padding: 0.9rem 1.75rem;
    display: flex; align-items: center; justify-content: space-between;
}
.topbar-title { font-size: 1rem; font-weight: 700; color: var(--brown); }
.topbar-sub   { font-size: 0.72rem; color: var(--text3); margin-top: 2px; }
.topbar-actions { display: flex; gap: 0.65rem; align-items: center; }
.badge {
    background: var(--brown-soft);
    border: 1px solid var(--brown-light);
    border-radius: 6px;
    padding: 0.3rem 0.75rem;
    font-size: 0.72rem; color: var(--brown); font-weight: 600;
}
.content { padding: 1.25rem 1.75rem; flex: 1; overflow-y: auto; }

/* ─── PANEL ───────────────────────────────────────────────── */
.panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(123,79,44,0.06);
}
.panel-header {
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.5rem;
    background: linear-gradient(90deg, var(--brown-soft) 0%, #fff 60%);
}
.panel-title {
    font-size: 0.92rem; font-weight: 700;
    display: flex; align-items: center; gap: 0.5rem;
    color: var(--brown);
}
.panel-title-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--sky);
    box-shadow: 0 0 0 3px rgba(14,165,201,0.15);
}

/* ─── SEARCH ──────────────────────────────────────────────── */
.search-input {
    border: 1px solid var(--border2); border-radius: 7px;
    padding: 0.4rem 0.75rem; font-size: 0.74rem;
    font-family: 'IBM Plex Sans Arabic', sans-serif;
    width: 200px; color: var(--text); background: var(--bg);
    outline: none; transition: border-color 0.13s;
}
.search-input:focus { border-color: var(--sky); box-shadow: 0 0 0 3px rgba(14,165,201,0.1); }

/* ─── TABLE ───────────────────────────────────────────────── */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
thead tr {
    background: linear-gradient(90deg, var(--brown-soft) 0%, #FBF6F1 100%);
    border-bottom: 2px solid var(--brown-light);
}
th {
    padding: 0.65rem 0.75rem;
    font-size: 0.67rem; font-weight: 700;
    color: var(--brown); letter-spacing: 0.06em;
    text-align: right; white-space: nowrap;
    text-transform: uppercase;
}
td {
    padding: 0.62rem 0.75rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle; text-align: right;
}
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--sky-soft); }
.hidden { display: none; }

/* ─── BUTTONS ─────────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 0.38rem;
    padding: 0.5rem 1rem;
    border: none; border-radius: 7px;
    font-family: 'IBM Plex Sans Arabic', sans-serif;
    font-size: 0.8rem; font-weight: 600;
    cursor: pointer; transition: all 0.13s; text-decoration: none;
}
.btn-primary  { background: var(--sky); color: #fff; }
.btn-primary:hover { background: var(--sky-dark); }
.btn-ghost {
    background: transparent;
    border: 1px solid var(--border2);
    color: var(--text2);
}
.btn-ghost:hover { border-color: var(--sky); color: var(--sky-dark); }
.btn-warning  { background: var(--brown); color: #fff; }
.btn-warning:hover { background: var(--brown2); }
.btn-sm { padding: 0.28rem 0.6rem; font-size: 0.71rem; }

/* ─── PROGRESS ────────────────────────────────────────────── */
.progress-wrap { display: inline-flex; align-items: center; gap: 0.5rem; }
.progress-bar-bg { display: inline-block; width: 90px; height: 6px; background: var(--border); border-radius: 3px; overflow: hidden; }
.progress-bar-fill { display: block; height: 100%; background: linear-gradient(90deg, var(--sky) 0%, var(--sky2) 100%); border-radius: 3px; }
.progress-count { font-size: 0.75rem; font-weight: 600; color: var(--text2); min-width: 40px; }

/* ─── EMPTY ───────────────────────────────────────────────── */
.empty { text-align: center; padding: 3rem 1.5rem; color: var(--text3); }
.empty-icon  { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4; }
.empty-title { font-size: 0.95rem; font-weight: 600; color: var(--text2); margin-bottom: 0.4rem; }
.empty-sub   { font-size: 0.8rem; }

/* ─── MODAL ───────────────────────────────────────────────── */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(43,26,14,0.6);
    z-index: 1000; align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; }
.modal {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px; padding: 1.5rem;
    width: 520px; max-width: 95vw; max-height: 90vh; overflow-y: auto;
    animation: modalIn 0.18s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.96) translateY(-8px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.1rem; }
.modal-title  { font-size: 0.95rem; font-weight: 700; color: var(--brown); }
.modal-close  { background: none; border: none; color: var(--text3); font-size: 1.2rem; cursor: pointer; }
.modal-close:hover { color: var(--brown); }
.detail-row { display: grid; grid-template-columns: 150px 1fr; gap: 0 0.75rem; padding: 0.55rem 0; border-bottom: 1px solid var(--border); font-size: 0.82rem; }
.detail-row:last-child { border-bottom: none; }
.detail-label { color: var(--text3); font-weight: 600; }
.detail-val   { color: var(--text); }

/* ─── SCROLLBAR ───────────────────────────────────────────── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }
    </style>
</head>
<body>
<div class="layout">

    <!-- ═══ SIDEBAR ══════════════════════════════════════════ -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">⚖️</div>
            <div>
                <div class="logo-text">نظام التأمينات</div>
                <div class="logo-sub">المحكمة الابتدائية بالجديدة</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">القائمة الرئيسية</div>
            <a href="{{ route('dashboard') }}" class="nav-item">
                <span class="nav-icon">🗂️</span> الملفات
            </a>
            <a href="{{ route('wathaiq.index') }}" class="nav-item active">
                <span class="nav-icon">📄</span> الوثائق المُنتجة
            </a>
        </nav>
        <div class="sidebar-footer">نظام معالجة حوادث الشغل</div>
    </aside>

    <!-- ═══ MAIN ══════════════════════════════════════════════ -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="topbar-title">
                    @isset($bayan)
                        البيان رقم {{ $bayan->group_index }} — سنة {{ $bayan->year }}
                    @else
                        الوثائق المُنتجة
                    @endisset
                </div>
                <div class="topbar-sub">
                    @isset($bayan)
                        نطاق الأرقام: {{ $bayan->range_label }} ({{ $bayan->dossiers->count() }} ملف)
                    @else
                        قائمة البيانات المُولَّدة — البيانات السنوية
                    @endisset
                </div>
            </div>
            <div class="topbar-actions">
                @isset($bayan)
                    <a href="{{ route('wathaiq.index') }}" class="btn btn-ghost btn-sm">← العودة للقائمة</a>
                @else
                    <span class="badge">📅 {{ now()->format('d/m/Y') }}</span>
                @endisset
            </div>
        </div>

        <div class="content">

            @if(session('success'))
                <div style="background:var(--success-soft);color:var(--success);border:1px solid #C0DD97;padding:0.72rem 1rem;border-radius:9px;margin-bottom:0.875rem;font-size:0.83rem;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <div class="panel-title-dot"></div>
                        @isset($bayan) البيانات المُنتجة @else البيانات المُنتجة @endisset
                    </div>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        @isset($bayan)
                            <input class="search-input" type="text" placeholder="بحث برقم الملف..." oninput="filterTable(this.value)" autocomplete="off">
                        @else
                            <span class="badge">{{ $bayans->count() }} بيان</span>
                        @endisset
                    </div>
                </div>

                {{-- ══ VUE LISTE DES BAYANS ══ --}}
                @isset($bayans)
                    @if($bayans->isEmpty())
                        <div class="empty">
                            <div class="empty-icon">📋</div>
                            <div class="empty-title">لا يوجد بيان مسجل بعد</div>
                            <div class="empty-sub">يتم إنشاء البيانات تلقائياً عند تسجيل الملفات وحفظها.</div>
                        </div>
                    @else
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>البيان رقم</th>
                                        <th>النطاق</th>
                                        <th>عدد الملفات</th>
                                        <th>الإجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bayans as $b)
                                    @php $percent = min(100, ($b->dossiers_count / 30) * 100); @endphp
                                    <tr>
                                        <td><strong style="color:var(--brown);">{{ $b->group_index }}</strong></td>
                                        <td>
                                            <span style="background:var(--brown-soft);color:var(--brown);padding:0.18rem 0.6rem;border-radius:5px;font-size:0.75rem;font-weight:600;">
                                                {{ $b->range_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress-wrap">
                                                <span class="progress-count">{{ $b->dossiers_count }} / 30</span>
                                                <span class="progress-bar-bg">
                                                    <span class="progress-bar-fill" style="width:{{ $percent }}%;"></span>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display:inline-flex;gap:0.4rem;align-items:center;">
                                                <a class="btn btn-primary btn-sm" href="{{ route('bayans.show', $b) }}">عرض البيان</a>
                                                <a class="btn btn-ghost btn-sm" href="{{ route('bayans.donn', $b) }}">📄 قائمة بيانات</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endisset

                {{-- ══ VUE DÉTAIL D'UN BAYAN ══ --}}
                @isset($bayan)
                    @if($bayan->dossiers->isEmpty())
                        <div class="empty">
                            <div class="empty-icon">📋</div>
                            <div class="empty-title">لا توجد ملفات مرتبطة بهذا البيان</div>
                            <div class="empty-sub">يتم ربط الملفات تلقائياً عند تسجيلها ضمن نطاق هذا البيان.</div>
                        </div>
                    @else
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الملف</th>
                                        <th>شركة التأمين</th>
                                        <th>ملفات</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach($bayan->dossiers as $d)
                                    @php
                                        $calc = $d->calcul;
                                        $detailPayload = [
                                            'numero_dossier'           => $d->numero_dossier,
                                            'numero_jugement'          => $d->numero_jugement,
                                            'date_jugement'            => $d->date_jugement?->format('Y-m-d'),
                                            'nom_victime'              => $d->nom_victime,
                                            'nom_assurance'            => $d->nom_assurance,
                                            'adresse_assurance'        => $d->adresse_assurance,
                                            'montant_initial'          => (float) $d->montant_initial,
                                            'masarif_janaza'           => (float) $d->masarif_janaza,
                                            'expertise'                => (float) $d->expertise,
                                            'total'                    => $calc ? (float) $calc->total : null,
                                            'numero_amr'               => $calc?->numero_amr_tanfidhi,
                                        ];
                                    @endphp
                                    <tr class="dossier-row" data-search="{{ strtolower($d->numero_dossier ?? '') }}">
                                        <td>{{ $d->id }}</td>
                                        <td><strong>{{ $d->numero_dossier ?? '—' }}</strong></td>
                                        <td>
                                            @if($d->nom_assurance_normalise)
                                                <span style="background:var(--sky-soft);color:var(--sky-dark);padding:0.18rem 0.6rem;border-radius:20px;font-size:0.67rem;font-weight:600;">
                                                    {{ $d->nom_assurance_normalise }}
                                                </span>
                                            @else
                                                {{ $d->nom_assurance ?? '—' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($calc)
                                                <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                                                    <a class="btn btn-ghost btn-sm" href="{{ route('dossiers.print.istidaa', $d) }}" target="_blank">استدعاء</a>
                                                    <a class="btn btn-ghost btn-sm" href="{{ route('dossiers.print.amr', $d) }}" target="_blank">أمر تنفيذي</a>
                                                </div>
                                            @else
                                                <span style="color:var(--text3);font-size:0.75rem;">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                data-detail="{{ json_encode($detailPayload, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) }}"
                                                onclick="openDetail(this)">عرض التفاصيل</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr id="emptySearch" class="hidden">
                                        <td colspan="5" style="text-align:center;color:var(--text3);padding:2rem;">لا توجد نتائج مطابقة.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endisset

            </div>
        </div>
    </main>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="detailModal" onclick="if(event.target===this)closeDetail()">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">تفاصيل الملف</div>
            <button type="button" class="modal-close" onclick="closeDetail()">✕</button>
        </div>
        <div id="detailBody"></div>
        <div style="margin-top:1rem;text-align:left;">
            <button type="button" class="btn btn-ghost" onclick="closeDetail()">إغلاق</button>
        </div>
    </div>
</div>

<script>
function filterTable(q) {
    q = (q || '').toLowerCase().trim();
    document.querySelectorAll('.dossier-row').forEach(function(tr) {
        var hay = tr.getAttribute('data-search') || '';
        tr.style.display = !q || hay.includes(q) ? '' : 'none';
    });
}
function openDetail(btn) {
    var d = JSON.parse(btn.getAttribute('data-detail'));
    var fields = [
        ['رقم الملف',             d.numero_dossier],
        ['شركة المشغِّلة',             d.numero_jugement],
        ['تاريخ القرار',          d.date_jugement],
        ['اسم المصاب',            d.nom_victime],
        ['شركة التأمين',          d.nom_assurance],
        ['العنوان',               d.adresse_assurance],
        ['مصاريف الجنازة',        d.masarif_janaza],
        ['الخبرة',                d.expertise],
        ['المبلغ المؤدى',         d.total != null ? d.total + ' درهم' : null],
        ['أمر تنفيذي',            d.numero_amr],
    ];
    var html = '';
    fields.forEach(function(f) {
        var val = (f[1] === null || f[1] === undefined || f[1] === '' || f[1] === 0) ? '—' : f[1];
        html += '<div class="detail-row">'
              + '<span class="detail-label">' + f[0] + '</span>'
              + '<span class="detail-val">'   + val   + '</span>'
              + '</div>';
    });
    document.getElementById('detailBody').innerHTML = html;
    document.getElementById('detailModal').classList.add('open');
}
function closeDetail() {
    document.getElementById('detailModal').classList.remove('open');
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDetail(); });
</script>
</body>
</html>