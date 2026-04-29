<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>البيان {{ $bayan->group_index }} — {{ $bayan->year }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
:root {
    --bg: #f8fafc;
    --surface: #ffffff;
    --surface2: #f1f5f9;
    --border: #e2e8f0;
    --border2: #cbd5e1;
    --text: #1e293b;
    --text2: #475569;
    --text3: #94a3b8;
    --accent: #7B4F2C;
    --accent2: #9C6B4A;
}
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Cairo', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; direction: rtl; }
        .layout { display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: 260px; background: var(--surface); border-left: 1px solid var(--border); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; flex-shrink: 0; }
        .sidebar-logo { padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.75rem; }
        .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .logo-text { font-size: 0.95rem; font-weight: 700; line-height: 1.2; }
        .logo-sub { font-size: 0.7rem; color: var(--text3); font-weight: 400; }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .nav-label { font-size: 0.65rem; font-weight: 700; color: var(--text3); letter-spacing: 0.1em; padding: 0.5rem 1.25rem; text-transform: uppercase; }
        .nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 1.25rem; color: var(--text2); font-size: 0.875rem; cursor: pointer; transition: all 0.15s; border-right: 3px solid transparent; margin: 1px 0; text-decoration: none; }
        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { font-weight: 700; background: rgba(123,79,44,0.1); color: var(--accent); border-right-color: var(--accent); }
        .nav-icon { font-size: 1rem; width: 20px; text-align: center; }
        .sidebar-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--border); font-size: 0.75rem; color: var(--text3); }

        /* MAIN */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0.875rem 2rem; display: flex; align-items: center; justify-content: space-between; }
        .topbar-title { font-size: 1rem; font-weight: 600; }
        .topbar-sub { font-size: 0.75rem; color: var(--text3); margin-top: 1px; }
        .topbar-actions { display: flex; gap: 0.75rem; align-items: center; }
        .badge { background: var(--surface2); border: 1px solid var(--border2); border-radius: 6px; padding: 0.3rem 0.75rem; font-size: 0.75rem; color: var(--text2); }
        .content { padding: 1.5rem 2rem; flex: 1; overflow-y: auto; }

        /* PANEL */
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
        .panel-title { font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .panel-title-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); flex-shrink: 0; }

        /* SEARCH */
        .search-wrap { position: relative; }
        .search-input { border: 1px solid var(--border2); border-radius: 8px; padding: 0.4rem 2rem 0.4rem 0.85rem; font-size: 0.78rem; font-family: 'Cairo', sans-serif; direction: rtl; width: 210px; color: var(--text); background: var(--surface2); outline: none; transition: border-color 0.15s, background 0.15s; }
        .search-input:focus { border-color: var(--accent); background: #fff; }
        .search-icon { position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); color: var(--text3); font-size: 0.85rem; pointer-events: none; }

        /* TABLE */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        thead tr { background: var(--surface2); border-bottom: 1px solid var(--border); }
        th { padding: 0.65rem 0.75rem; font-size: 0.7rem; font-weight: 700; color: var(--text3); letter-spacing: 0.04em; text-align: right; white-space: nowrap; }
        td { padding: 0.65rem 0.75rem; border-bottom: 1px solid var(--border); vertical-align: middle; text-align: right; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(123,79,44,0.05); }
        .hidden { display: none; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border: none; border-radius: 8px; font-family: 'Cairo', sans-serif; font-size: 0.83rem; font-weight: 600; cursor: pointer; transition: all 0.15s; text-decoration: none; }
        .btn-ghost { background: var(--surface); border: 1px solid var(--border2); color: var(--text2); }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: rgba(123,79,44,0.05); }
        .btn-primary { background: var(--accent); color: #fff; border: none; }
        .btn-primary:hover { background: var(--accent2); }
        .btn-sm { padding: 0.3rem 0.65rem; font-size: 0.72rem; }

        /* EMPTY */
        .empty { text-align: center; padding: 3rem 1.5rem; color: var(--text3); }
        .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4; }
        .empty-title { font-size: 0.95rem; font-weight: 600; color: var(--text2); margin-bottom: 0.4rem; }
        .empty-sub { font-size: 0.8rem; }

        /* MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.open { display: flex; }
        .modal { background: var(--surface); border-radius: 14px; max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 1.5rem; }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.1rem; }
        .modal-header h2 { font-size: 1rem; font-weight: 700; color: var(--text); }
        .modal-close { background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--text3); line-height: 1; }
        .modal-close:hover { color: var(--text); }
        .detail-row { display: grid; grid-template-columns: 150px 1fr; gap: 0 0.75rem; padding: 0.55rem 0; border-bottom: 1px solid var(--border); font-size: 0.82rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: var(--text3); font-weight: 600; }
        .detail-val { color: var(--text); }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }
    </style>
</head>
<body>
<div class="layout">

    <!-- SIDEBAR -->
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
            <a href="{{ route('home') }}" class="nav-item">
                <span class="nav-icon">🗂️</span> الملفات
            </a>
            <a href="{{ route('wathaiq.index') }}" class="nav-item active">
                <span class="nav-icon">📄</span> الوثائق المُنتجة
            </a>
        </nav>
        <div class="sidebar-footer">نظام معالجة حوادث الشغل — v1.0</div>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="topbar-title">البيان رقم {{ $bayan->group_index }}  سنة {{ $bayan->year }}</div>
                <div class="topbar-sub">نطاق الأرقام: {{ $bayan->range_label }} — {{ $bayan->dossiers->count() }} ملف</div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('wathaiq.index') }}" class="btn btn-ghost btn-sm">← العودة للقائمة</a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <div class="panel-title-dot"></div>
                        البيانات المُنتجة
                    </div>
                    <div class="search-wrap">
                        <span class="search-icon">🔍</span>
                        <input
                            id="searchInput"
                            class="search-input"
                            type="text"
                            placeholder="بحث برقم الملف..."
                            oninput="filterTable()"
                            autocomplete="off"
                        />
                    </div>
                </div>

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
                                        $calc      = $d->calcul;
                                        $typeLabel = \App\Services\CalculService::typeMalafLabel($d->type_cas, $d->type_malaf);
                                        $detailPayload = [
                                            'numero_dossier'           => $d->numero_dossier,
                                            'numero_jugement'          => $d->numero_jugement,
                                            'date_jugement'            => $d->date_jugement?->format('Y-m-d'),
                                            'nom_victime'              => $d->nom_victime,
                                            'nom_assurance'            => $d->nom_assurance,
                                            'nom_assurance_normalise'  => $d->nom_assurance_normalise,
                                            'adresse_assurance'        => $d->adresse_assurance,
                                            'type_malaf'               => $typeLabel,
                                            'montant_initial'          => (float) $d->montant_initial,
                                            'montant_rasemal_ijmali'   => (float) $d->montant_rasemal_ijmali,
                                            'montant_taawidat_youmiya' => (float) $d->montant_taawidat_youmiya,
                                            'masarif_janaza'           => (float) $d->masarif_janaza,
                                            'expertise'                => (float) $d->expertise,
                                            'beneficiaires'            => $d->beneficiaires_json,
                                            'total'                    => $calc ? (float) $calc->total : null,
                                            'numero_amr'               => $calc?->numero_amr_tanfidhi,
                                        ];
                                    @endphp
                                    <tr data-dossier="{{ strtolower($d->numero_dossier ?? '') }}">
                                        <td>{{ $d->id }}</td>
                                        <td><strong>{{ $d->numero_dossier ?? '—' }}</strong></td>
                                        <td>{{ $d->nom_assurance_normalise ?? $d->nom_assurance ?? '—' }}</td>
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
                                            <button
                                                type="button"
                                                class="btn btn-primary btn-sm"
                                                data-detail="{{ json_encode($detailPayload, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) }}"
                                                onclick="openDetail(this)"
                                            >عرض التفاصيل</button>
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
            </div>
        </div>
    </main>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="detailModal" onclick="if(event.target===this) closeDetail()">
    <div class="modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2>تفاصيل الملف</h2>
            <button type="button" class="modal-close" onclick="closeDetail()">×</button>
        </div>
        <div id="detailBody"></div>
    </div>
</div>

<script>
    /* ── Search ── */
    function filterTable() {
        var val = document.getElementById('searchInput').value.trim().toLowerCase();
        var rows = document.querySelectorAll('#tableBody tr[data-dossier]');
        var anyVisible = false;
        rows.forEach(function(row) {
            if (!val) { row.classList.remove('hidden'); anyVisible = true; return; }
            if ((row.getAttribute('data-dossier') || '').includes(val)) {
                row.classList.remove('hidden'); anyVisible = true;
            } else {
                row.classList.add('hidden');
            }
        });
        document.getElementById('emptySearch').classList.toggle('hidden', anyVisible || !val);
    }

    /* ── Detail Modal ── */
    function openDetail(btn) {
        var d = JSON.parse(btn.getAttribute('data-detail'));
        var fields = [
            ['رقم الملف',              d.numero_dossier],
            ['رقم الحكم',              d.numero_jugement],
            ['تاريخ القرار',           d.date_jugement],
            ['اسم المصاب',             d.nom_victime],
            ['شركة التأمين',           d.nom_assurance],
            ['التأمين (معياري)',        d.nom_assurance_normalise],
            ['العنوان',                d.adresse_assurance],
            ['نوع الملف',              d.type_malaf],
            ['المبلغ الأصلي',          d.montant_initial  != null ? d.montant_initial  + ' درهم' : null],
            ['رأسمال إجمالي',          d.montant_rasemal_ijmali],
            ['تعويضات يومية',          d.montant_taawidat_youmiya],
            ['مصاريف الجنازة',         d.masarif_janaza],
            ['الخبرة',                 d.expertise],
            ['المستفيدون',             typeof d.beneficiaires === 'object' ? JSON.stringify(d.beneficiaires) : d.beneficiaires],
            ['المبلغ المؤدى (محسوب)',  d.total != null ? d.total + ' درهم' : null],
            ['أمر تنفيذي',             d.numero_amr],
        ];
        var html = '';
        fields.forEach(function(f) {
            var val = (f[1] === null || f[1] === undefined || f[1] === '') ? '—' : f[1];
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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDetail();
    });
</script>
</body>
</html>