<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>البيان {{ $bayan->group_index }} — {{ $bayan->year }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #7B4F2C; --border: #e2e8f0; --text: #1e293b; --muted: #64748b; }
        * { box-sizing: border-box; }
        body { font-family: Cairo, sans-serif; margin: 0; padding: 1.5rem; background: #f8fafc; color: var(--text); }
        .top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
        h1 { font-size: 1.15rem; margin: 0 0 0.35rem; }
        .sub { color: var(--muted); font-size: 0.85rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.45rem 0.9rem; border-radius: 8px; text-decoration: none; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; font-family: inherit; }
        .btn-ghost { background: #fff; border: 1px solid var(--border); color: var(--text); }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
        .panel { background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        th { text-align: right; padding: 0.65rem 0.75rem; background: #f1f5f9; color: var(--muted); font-size: 0.7rem; }
        td { padding: 0.65rem 0.75rem; border-top: 1px solid var(--border); vertical-align: middle; }
        .actions { display: flex; gap: 0.35rem; flex-wrap: wrap; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 14px; max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 1.25rem; }
        .modal h2 { margin: 0 0 1rem; font-size: 1rem; }
        .detail-row { display: grid; grid-template-columns: 140px 1fr; gap: 0.35rem 0.75rem; font-size: 0.82rem; margin-bottom: 0.5rem; }
        .detail-row span:first-child { color: var(--muted); }
        .modal-close { float: left; background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--muted); }
    </style>
</head>
<body>
    <div class="top">
        <div>
            <h1>البيان رقم {{ $bayan->group_index }} — سنة {{ $bayan->year }}</h1>
            <div class="sub">نطاق الأرقام: {{ $bayan->range_label }} — {{ $bayan->dossiers->count() }} ملف</div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-ghost">← العودة للوحة</a>
    </div>

    <div class="panel">
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
            <tbody>
                @forelse($bayan->dossiers as $d)
                    @php
                        $calc = $d->calcul;
                        $typeLabel = \App\Services\CalculService::typeMalafLabel($d->type_cas, $d->type_malaf);
                        $detailPayload = [
                            'numero_dossier' => $d->numero_dossier,
                            'numero_jugement' => $d->numero_jugement,
                            'date_jugement' => $d->date_jugement?->format('Y-m-d'),
                            'nom_victime' => $d->nom_victime,
                            'nom_assurance' => $d->nom_assurance,
                            'nom_assurance_normalise' => $d->nom_assurance_normalise,
                            'adresse_assurance' => $d->adresse_assurance,
                            'type_malaf' => $typeLabel,
                            'montant_initial' => (float) $d->montant_initial,
                            'montant_rasemal_ijmali' => (float) $d->montant_rasemal_ijmali,
                            'montant_taawidat_youmiya' => (float) $d->montant_taawidat_youmiya,
                            'masarif_janaza' => (float) $d->masarif_janaza,
                            'expertise' => (float) $d->expertise,
                            'beneficiaires' => $d->beneficiaires_json,
                            'total' => $calc ? (float) $calc->total : null,
                            'numero_amr' => $calc?->numero_amr_tanfidhi,
                        ];
                    @endphp
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td><strong>{{ $d->numero_dossier ?? '—' }}</strong></td>
                        <td>{{ $d->nom_assurance_normalise ?? $d->nom_assurance ?? '—' }}</td>
                        <td>
                            @if($calc)
                                <div class="actions">
                                    <a class="btn btn-ghost" href="{{ route('dossiers.print.istidaa', $d) }}" target="_blank">استدعاء</a>
                                    <a class="btn btn-ghost" href="{{ route('dossiers.print.amr', $d) }}" target="_blank">أمر تنفيذي</a>
                                </div>
                            @else
                                <span class="sub">—</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-ghost" data-detail="{{ json_encode($detailPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}" onclick="openDetail(this)">عرض التفاصيل</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:2rem;">لا توجد ملفات مرتبطة بهذا البيان.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal-overlay" id="detailModal" onclick="if(event.target===this) closeDetail()">
        <div class="modal" onclick="event.stopPropagation()">
            <button type="button" class="modal-close" onclick="closeDetail()">×</button>
            <h2>تفاصيل الملف</h2>
            <div id="detailBody"></div>
        </div>
    </div>

    <script>
        function openDetail(btn) {
            var raw = btn.getAttribute('data-detail');
            var d = JSON.parse(raw);
            var html = '';
            function row(label, val) {
                if (val === null || val === undefined || val === '') val = '—';
                if (typeof val === 'object') val = JSON.stringify(val);
                html += '<div class="detail-row"><span>' + label + '</span><span>' + val + '</span></div>';
            }
            row('رقم الملف', d.numero_dossier);
            row('رقم الحكم', d.numero_jugement);
            row('تاريخ القرار', d.date_jugement);
            row('اسم المصاب', d.nom_victime);
            row('شركة التأمين', d.nom_assurance);
            row('التأمين (معياري)', d.nom_assurance_normalise);
            row('العنوان', d.adresse_assurance);
            row('نوع الملف', d.type_malaf);
            row('المبلغ الأصلي', d.montant_initial != null ? d.montant_initial + ' درهم' : '—');
            row('رأسمال إجمالي', d.montant_rasemal_ijmali);
            row('تعويضات يومية', d.montant_taawidat_youmiya);
            row('مصاريف الجنازة', d.masarif_janaza);
            row('الخبرة', d.expertise);
            row('المستفيدون', d.beneficiaires);
            row('المبلغ المؤدى (محسوب)', d.total != null ? d.total + ' درهم' : '—');
            row('أمر تنفيذي', d.numero_amr);
            document.getElementById('detailBody').innerHTML = html;
            document.getElementById('detailModal').classList.add('open');
        }
        function closeDetail() {
            document.getElementById('detailModal').classList.remove('open');
        }
    </script>
</body>
</html>
