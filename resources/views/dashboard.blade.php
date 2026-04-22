<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نظام معالجة ملفات التأمين</title>
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
    --brown-light: #E3D5CA;
    --brown-bg: #F8F3EE;
    --success: #22c55e;
    --warning: #f59e0b;
    --error: #ef4444;
    --gold: #eab308;
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

        /* PANELS */
        .panels-grid { display: flex; flex-direction: column; gap: 1.25rem; }
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .panel-title-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }

        /* UPLOAD */
        .upload-zone { border: 1px dashed var(--border2); padding: 0.5rem; border-radius: 8px; max-width: 300px; cursor: pointer; transition: all 0.2s; }
        .upload-zone:hover { border-color: var(--accent); background: rgba(139,94,60,0.08); }
        .upload-icon { font-size: 1.5rem; margin-bottom: 0.3rem; }
        .upload-title { font-size: 0.7rem; font-weight: 500; }
        .upload-sub { font-size: 0.65rem; }
        input[type="file"] { display: none; }

        /* FORM */
        .modal-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .modal-form-grid .full { grid-column: 1/-1; }
        .form-group { display: flex; flex-direction: column; gap: 0.3rem; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 0.72rem; font-weight: 600; color: var(--text3); letter-spacing: 0.02em; }
        input, select, textarea { background: var(--bg); border: 1px solid var(--border2); border-radius: 8px; padding: 0.5rem 0.75rem; color: var(--text); font-family: 'Cairo', sans-serif; font-size: 0.83rem; transition: border-color 0.15s; width: 100%; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--accent); }
        select option { background: var(--surface2); }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border: none; border-radius: 8px; font-family: 'Cairo', sans-serif; font-size: 0.83rem; font-weight: 600; cursor: pointer; transition: all 0.15s; text-decoration: none; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent2); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { filter: brightness(1.1); }
        .btn-warning { background: var(--warning); color: #000; }
        .btn-warning:hover { filter: brightness(1.1); }
        .btn-danger { background: var(--error); color: #fff; }
        .btn-danger:hover { filter: brightness(1.1); }
        .btn-ghost { background: transparent; border: 1px solid var(--border2); color: var(--text2); }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.72rem; }
        .btn-full { width: 100%; justify-content: center; }

        /* TABLE */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        thead tr { background: var(--surface2); border-bottom: 1px solid var(--border); }
        th { padding: 0.65rem 0.75rem; font-size: 0.7rem; font-weight: 700; color: var(--text3); letter-spacing: 0.04em; text-align: right; white-space: nowrap; }
        td { padding: 0.65rem 0.75rem; border-bottom: 1px solid var(--border); vertical-align: middle; text-align: right; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(123,79,44,0.05); }

        /* CHIPS */
        .chip { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.68rem; font-weight: 600; }
        .chip-blue { background: rgba(123,79,44,0.15); color: var(--accent); }
        .chip-green { background: rgba(16,185,129,0.15); color: #34d399; }
        .chip-warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .chip-muted { background: var(--surface2); color: var(--text3); }
        .chip::before { content: '●'; font-size: 0.5rem; }

        /* ALERTS */
        .alert { padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: 0.85rem; display: flex; align-items: flex-start; gap: 0.5rem; }
        .alert-success { background: rgba(16,185,129,0.1); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
        .alert-error { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.25); }

        /* EMPTY */
        .empty { text-align: center; padding: 3rem 1.5rem; color: var(--text3); }
        .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.5; }
        .empty-title { font-size: 0.9rem; font-weight: 600; color: var(--text2); margin-bottom: 0.25rem; }

        /* ACTIONS */
        .actions-row { display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap; }

        /* SAVE CHECKBOX */
        .save-checkbox-cell { text-align: center !important; }
        .save-checkbox-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; }
        .custom-checkbox { width: 18px; height: 18px; accent-color: var(--success); cursor: pointer; }
        .save-btn-inline { font-size: 0.62rem; padding: 0.2rem 0.4rem; background: var(--success); color: #fff; border: none; border-radius: 5px; cursor: pointer; font-family: 'Cairo', sans-serif; display: none; }
        .save-btn-inline.visible { display: inline-flex; }

        /* MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: var(--surface); border: 1px solid var(--border2); border-radius: 16px; padding: 1.5rem; width: 480px; max-width: 95vw; max-height: 92vh; overflow-y: auto; animation: modalIn 0.2s ease; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(-10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        .modal-title { font-size: 1rem; font-weight: 700; }
        .modal-close { background: none; border: none; color: var(--text3); font-size: 1.3rem; cursor: pointer; line-height: 1; }
        .modal-close:hover { color: var(--text); }
        .modal-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; justify-content: flex-end; }

        /* PAGINATION */
        .pagination { display: flex; gap: 0.5rem; align-items: center; justify-content: center; padding: 1rem; border-top: 1px solid var(--border); font-size: 0.8rem; color: var(--text3); }
        .pagination a { color: var(--accent); }

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
            {{-- الصفحة الحالية: الملفات --}}
            <a href="{{ url('/dashboard') }}" class="nav-item active">
                <span class="nav-icon">🗂️</span> الملفات
            </a>
            {{-- رابط للصفحة المنفصلة للوثائق --}}
            <a href="{{ route('wathaiq.index') }}" class="nav-item">
                <span class="nav-icon">📄</span> الوثائق المُنتجة
            </a>
        </nav>
        <div class="sidebar-footer">نظام معالجة حوادث الشغل — v1.0</div>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">لوحة تتبع الملفات</div>
                <div class="topbar-sub">رفع الملفات — استخراج البيانات — الحساب — توليد الوثائق</div>
            </div>
            <div class="topbar-actions">
                <span class="badge">📅 {{ now()->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="content">

            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">❌ @foreach($errors->all() as $err) {{ $err }} @endforeach</div>
            @endif

            <div class="panels-grid">

                <!-- UPLOAD PDF -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <div class="panel-title-dot"></div>
                            رفع ملف PDF
                        </div>
                    </div>
                    <form method="post" action="{{ route('upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div style="padding: 1.25rem;">
                         <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon" id="uploadIcon">📤</div>
                        <div class="upload-title" id="uploadTitle">اسحب الملف هنا أو انقر للاختيار</div>
                        <div class="upload-sub" id="fileName">PDF أو JPG أو PNG — بحد أقصى 80 ميغابايت</div>
                        <input type="file" name="document" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" required
                            onchange="handleFileSelect(this)">
                    </div>
                        </div>
                        <div style="padding: 0 1.25rem 1.25rem; display:flex; justify-content:flex-end;">
                            <button type="submit" class="btn btn-primary">تحليل الملف</button>
                        </div>
                    </form>
                </div>

                <!-- TABLE DES DOSSIERS -->
                <div class="panel">
                    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                        <div>
                            <input type="text" id="tableSearch" placeholder="بحث..." oninput="filterDossierTable(this.value)"
                                   style="padding:6px 10px;border:1px solid var(--border2);border-radius:6px;font-size:0.75rem;width:200px;">
                        </div>
                        <div class="panel-title" style="flex:1; justify-content:center;">
                            <div class="panel-title-dot"></div>
                            قائمة الملفات
                        </div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <span class="badge">{{ $dossiers->total() }} ملف</span>
                            <button type="button" class="btn btn-success btn-sm" onclick="openManualModal()">➕ إضافة يدوية</button>
                        </div>
                    </div>

                    @if($dossiers->isEmpty())
                        <div class="empty">
                            <div class="empty-icon">📂</div>
                            <div class="empty-title">لا توجد ملفات بعد</div>
                            قم برفع ملف PDF أو إضافة ملف يدوياً
                        </div>
                    @else
                        <div class="table-wrap">
                            <table id="dossiersTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>رقم الملف</th>
                                        <th>الملف</th>
                                        <th>شركة التأمين</th>
                                        <th>المبلغ الأصلي</th>
                                        <th>المبلغ المؤدى</th>
                                        <th>ملفات</th>
                                        <th>إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dossiers as $d)
                                        @php
                                            $payload = [
                                                'type_cas' => $d->type_cas,
                                                'type_malaf' => $d->type_malaf,
                                                'montant_initial' => (float) $d->montant_initial,
                                                'montant_rasemal_ijmali' => (float) $d->montant_rasemal_ijmali,
                                                'montant_taawidat_youmiya' => (float) $d->montant_taawidat_youmiya,
                                                'masarif_janaza' => (float) $d->masarif_janaza,
                                                'expertise' => (float) $d->expertise,
                                                'beneficiaires_json' => $d->beneficiaires_json ?? [],
                                            ];
                                            $payloadAttr = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                                        @endphp
                                        <tr id="row-{{ $d->id }}" class="dossier-row" data-search="{{ strtolower(($d->numero_dossier ?? '').' '.($d->nom_assurance_normalise ?? '').' '.($d->nom_assurance ?? '')) }}">
                                            <td class="save-checkbox-cell">
                                                <div class="save-checkbox-wrap">
                                                    <input type="checkbox" class="custom-checkbox" id="chk-{{ $d->id }}"
                                                        data-id="{{ $d->id }}"
                                                        onchange="toggleSaveBtn(this.dataset.id, this.checked)"
                                                        {{ $d->saved ? 'checked' : '' }} @if(!$d->calcul) disabled title="يجب إجراء الحساب أولاً" @endif>
                                                    <button type="button" class="save-btn-inline {{ $d->saved ? 'visible' : '' }}" 
                                                            id="savebtn-{{ $d->id }}" 
                                                            data-id="{{ $d->id }}"
                                                            onclick="saveDossier(this.dataset.id)">💾</button>
                                                </div>
                                            </td>
                                            <td style="color:var(--text3)">{{ $d->id }}</td>
                                            <td>
                                                <strong>{{ $d->numero_dossier ?: '—' }}</strong>
                                                @if($d->date_jugement)
                                                    <div style="font-size:0.7rem;color:var(--text3)">{{ $d->date_jugement->format('d/m/Y') }}</div>
                                                @endif
                                            </td>
                                          <td style="max-width:140px;font-size:0.78rem;">
                                                @if($d->fichier_pdf)
                                                    <a href="{{ asset('storage/'.$d->fichier_pdf) }}" target="_blank"
                                                    style="display:inline-flex;align-items:center;gap:0.3rem;color:var(--accent);text-decoration:none;">
                                                        📄 ملف
                                                    </a>
                                                @else
                                                    <span style="color:var(--text3)">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->nom_assurance_normalise)
                                                    <span class="chip chip-blue">{{ $d->nom_assurance_normalise }}</span>
                                                @else
                                                    <span style="color:var(--text3)">—</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format((float) $d->montant_initial, 2, '.', ' ') }}</td>
                                            <td>
                                                @if($d->calcul)
                                                    <button type="button" class="btn btn-ghost btn-sm" 
                                                            data-id="{{ $d->id }}"
                                                            onclick="openBreakdownServer(this.dataset.id)">
                                                        {{ number_format((float) $d->calcul->total, 2, '.', ' ') }}
                                                    </button>
                                                @elseif($d->type_cas)
                                                    <button type="button" class="btn btn-ghost btn-sm" data-payload="{{ $payloadAttr }}" onclick="openBreakdownPreview(this)">معاينة</button>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->calcul)
                                                    <a class="btn btn-ghost btn-sm" target="_blank" href="{{ route('dossiers.print.istidaa', $d) }}">استدعاء</a>
                                                    <a class="btn btn-ghost btn-sm" target="_blank" href="{{ route('dossiers.print.amr', $d) }}">أمر</a>
                                                    
                                                @else
                                                    <span style="color:var(--text3)">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="actions-row">
                                                    <button type="button" class="btn btn-primary btn-sm" 
        data-payload="{{ $payloadAttr }}" 
        data-id="{{ $d->id }}"
        onclick="openCalcModal(this.dataset.id, this)">تسجيل</button>
                                                    <button type="button" class="btn btn-warning btn-sm" onclick="openEditModalFromButton(this)"
                                                            data-id="{{ $d->id }}"
                                                            data-numero_dossier="{{ e($d->numero_dossier) }}"
                                                            data-numero_jugement="{{ e($d->numero_jugement) }}"
                                                            data-date_jugement="{{ $d->date_jugement?->format('Y-m-d') }}"
                                                            data-nom_victime="{{ e($d->nom_victime) }}"
                                                            data-nom_assurance="{{ e($d->nom_assurance) }}"
                                                            data-adresse_assurance="{{ e($d->adresse_assurance) }}"
                                                            data-montant_initial="{{ $d->montant_initial }}"
                                                            data-montant_rasemal_ijmali="{{ $d->montant_rasemal_ijmali }}"
                                                            data-montant_taawidat_youmiya="{{ $d->montant_taawidat_youmiya }}"
                                                            data-masarif_janaza="{{ $d->masarif_janaza }}"
                                                            data-expertise="{{ $d->expertise }}"
                                                            data-type_cas="{{ $d->type_cas }}"
                                                            data-type_malaf="{{ e($d->type_malaf) }}"
                                                            data-beneficiaires="{{ json_encode($d->beneficiaires_json ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">✏️</button>
                                                    <form method="post" action="{{ route('dossiers.destroy', $d) }}" onsubmit="return confirm('حذف هذا الملف؟');" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($dossiers->hasPages())
                            <div class="pagination">
                                @if($dossiers->onFirstPage()) <span>السابق</span>
                                @else <a href="{{ $dossiers->previousPageUrl() }}">السابق</a> @endif
                                <span>صفحة {{ $dossiers->currentPage() }} / {{ $dossiers->lastPage() }}</span>
                                @if($dossiers->hasMorePages()) <a href="{{ $dossiers->nextPageUrl() }}">التالي</a>
                                @else <span>التالي</span> @endif
                            </div>
                        @endif
                    @endif
                </div>

                <p style="text-align:center;color:var(--text3);font-size:0.8rem;padding:0 1rem 1rem;">
                    لإضافة ملف يدوياً استخدم زر <strong>إضافة يدوية</strong> أعلى الجدول.
                </p>

            </div><!-- /panels-grid -->
        </div><!-- /content -->
    </main>
</div>

<!-- تفاصيل المبلغ المؤدى -->
<div class="modal-overlay" id="breakdownModal">
    <div class="modal" style="max-width:420px;">
        <div class="modal-header">
            <div class="modal-title">تفاصيل المبلغ المؤدى</div>
            <button type="button" class="modal-close" onclick="document.getElementById('breakdownModal').classList.remove('open')">✕</button>
        </div>
        <table style="width:100%;font-size:0.85rem;">
            <tbody id="breakdownBody"></tbody>
        </table>
        <div class="modal-actions">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('breakdownModal').classList.remove('open')">إغلاق</button>
        </div>
    </div>
</div>

<!-- حساب -->
<div class="modal-overlay" id="calcModal">
    <div class="modal" style="max-width:520px;">
        <div class="modal-header">
            <div class="modal-title">إعداد الحساب</div>
            <button type="button" class="modal-close" onclick="document.getElementById('calcModal').classList.remove('open')">✕</button>
        </div>
        <form id="calcForm" method="post" action="">
            @csrf
            <div class="modal-form-grid">
                <div class="form-group full">
                    <label>نوع الملف (الحالة)</label>
                    <select name="type_cas" id="calc_type_cas" required onchange="toggleCalcExtra()">
                        <option value="irad_omri">إيراد عمري (×10)</option>
                        <option value="irad_omri_ras_mal">إيراد عمري محول إلى رأس مال</option>
                        <option value="masdar_total_taawidat">رأسمال إجمالي + تعويضات يومية</option>
                        <option value="gharama_ijbariya">غرامة إجبارية</option>
                        <option value="wafaya_irad_omri">وفاة — إيراد عمري (مستفيدون)</option>
                        <option value="wafaya_ras_mal">وفاة — رأس مال (مستفيدون)</option>
                        <option value="autre">أخرى</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label>وصف الملف (اختياري)</label>
                    <input type="text" name="type_malaf" id="calc_type_malaf" placeholder="يظهر في عمود الملف">
                </div>
                <div class="form-group">
                    <label>المبلغ الأصلي</label>
                    <input type="number" step="0.01" min="0" name="montant_initial" id="calc_montant_initial" oninput="updateCalcPreview()">
                </div>
                <div class="form-group">
                    <label>الخبرة</label>
                    <input type="number" step="0.01" min="0" name="expertise" id="calc_expertise" oninput="updateCalcPreview()">
                </div>
                <div class="form-group" id="wrap_rasemal" style="display:none">
                    <label>رأسمال إجمالي</label>
                    <input type="number" step="0.01" min="0" name="montant_rasemal_ijmali" id="calc_montant_rasemal_ijmali" oninput="updateCalcPreview()">
                </div>
                <div class="form-group" id="wrap_taawidat" style="display:none">
                    <label>تعويضات يومية</label>
                    <input type="number" step="0.01" min="0" name="montant_taawidat_youmiya" id="calc_montant_taawidat_youmiya" oninput="updateCalcPreview()">
                </div>
                <div class="form-group" id="wrap_janaza" style="display:none">
                    <label>مصاريف الجنازة</label>
                    <input type="number" step="0.01" min="0" name="masarif_janaza" id="calc_masarif_janaza" oninput="updateCalcPreview()">
                </div>
                <div class="form-group full" id="wrap_benef" style="display:none">
                    <label>مبالغ المستفيدين (درهم)</label>
                    <div id="calc_benef_list"></div>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="addCalcBenefRow('')">+ مستفيد</button>
                </div>
            </div>
            <p style="font-size:0.75rem;color:var(--text3);margin:0.5rem 0;">معاينة: <strong id="calcPreviewTotal">—</strong> درهم</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('calcModal').classList.remove('open')">إلغاء</button>
                <button type="button" class="btn btn-ghost" onclick="previewCalcFromForm()">تفاصيل</button>
                <button type="submit" class="btn btn-primary">حفظ الحساب</button>
            </div>
        </form>
    </div>
</div>

<!-- إضافة يدوية -->
<div class="modal-overlay" id="manualModal">
    <div class="modal" style="max-width:540px;">
        <div class="modal-header">
            <div class="modal-title">إضافة ملف يدوياً</div>
            <button type="button" class="modal-close" onclick="document.getElementById('manualModal').classList.remove('open')">✕</button>
        </div>
        <form method="post" action="{{ route('manual.store') }}">
            @csrf
            <div class="modal-form-grid">
                <div class="form-group"><label>رقم الملف</label><input type="text" name="numero_dossier"></div>
                <div class="form-group"><label>رقم الحكم</label><input type="text" name="numero_jugement"></div>
                <div class="form-group"><label>تاريخ القرار</label><input type="date" name="date_jugement"></div>
                <div class="form-group"><label>شركة التأمين</label><input type="text" name="nom_assurance"></div>
                <div class="form-group full"><label>عنوان الشركة</label><input type="text" name="adresse_assurance"></div>
                <div class="form-group"><label>المبلغ الأصلي</label><input type="number" step="0.01" min="0" name="montant_initial"></div>
                <div class="form-group"><label>الخبرة</label><input type="number" step="0.01" min="0" name="expertise"></div>
                <div class="form-group"><label>رأسمال إجمالي</label><input type="number" step="0.01" min="0" name="montant_rasemal_ijmali" value="0"></div>
                <div class="form-group"><label>تعويضات يومية</label><input type="number" step="0.01" min="0" name="montant_taawidat_youmiya" value="0"></div>
                <div class="form-group"><label>مصاريف الجنازة</label><input type="number" step="0.01" min="0" name="masarif_janaza" value="0"></div>
                <div class="form-group full">
                    <label>نوع الحالة</label>
                    <select name="type_cas">
                        <option value="">—</option>
                        <option value="irad_omri">إيراد عمري</option>
                        <option value="irad_omri_ras_mal">رأس مال</option>
                        <option value="masdar_total_taawidat">رأسمال + تعويضات</option>
                        <option value="gharama_ijbariya">غرامة إجبارية</option>
                        <option value="wafaya_irad_omri">وفاة إيراد عمري</option>
                        <option value="wafaya_ras_mal">وفاة رأس مال</option>
                        <option value="autre">أخرى</option>
                    </select>
                </div>
                <div class="form-group full"><label>وصف الملف</label><input type="text" name="type_malaf"></div>
                <div class="form-group full"><label>اسم المصاب</label><input type="text" name="nom_victime"></div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('manualModal').classList.remove('open')">إلغاء</button>
                <button type="submit" class="btn btn-success">تسجيل</button>
            </div>
        </form>
    </div>
</div>

<!-- تعديل الملف -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">✏️ تعديل الملف</div>
            <button class="modal-close" onclick="closeEditModal()">✕</button>
        </div>
        <form id="editForm" method="post" action="">
            @csrf
            @method('PUT')
            <div class="modal-form-grid">
                <div class="form-group"><label>رقم الملف</label><input type="text" name="numero_dossier" id="edit_numero_dossier"></div>
                <div class="form-group"><label>رقم الحكم</label><input type="text" name="numero_jugement" id="edit_numero_jugement"></div>
                <div class="form-group"><label>تاريخ القرار</label><input type="date" name="date_jugement" id="edit_date_jugement"></div>
                <div class="form-group"><label>اسم المصاب</label><input type="text" name="nom_victime" id="edit_nom_victime"></div>
                <div class="form-group"><label>شركة التأمين</label><input type="text" name="nom_assurance" id="edit_nom_assurance"></div>
                <div class="form-group"><label>المبلغ الأصلي</label><input type="number" step="0.01" name="montant_initial" id="edit_montant_initial"></div>
                <div class="form-group"><label>الخبرة</label><input type="number" step="0.01" name="expertise" id="edit_expertise"></div>
                <div class="form-group">
                    <label>نوع الحالة</label>
                    <select name="type_cas" id="edit_type_cas">
                        <option value="">اختر النوع</option>
                        <option value="irad_omri">إيراد عمري (×10)</option>
                        <option value="irad_omri_ras_mal">إيراد عمري رأس مال</option>
                        <option value="masdar_total_taawidat">رأسمال إجمالي + تعويضات</option>
                        <option value="gharama_ijbariya">غرامة إجبارية</option>
                        <option value="wafaya_irad_omri">وفاة — إيراد عمري</option>
                        <option value="wafaya_ras_mal">وفاة — رأس مال</option>
                        <option value="autre">أخرى</option>
                    </select>
                </div>
                <div class="form-group full"><label>وصف الملف</label><input type="text" name="type_malaf" id="edit_type_malaf"></div>
                <div class="form-group"><label>رأسمال إجمالي</label><input type="number" step="0.01" min="0" name="montant_rasemal_ijmali" id="edit_montant_rasemal_ijmali"></div>
                <div class="form-group"><label>تعويضات يومية</label><input type="number" step="0.01" min="0" name="montant_taawidat_youmiya" id="edit_montant_taawidat_youmiya"></div>
                <div class="form-group"><label>مصاريف الجنازة</label><input type="number" step="0.01" min="0" name="masarif_janaza" id="edit_masarif_janaza"></div>
                <div class="form-group full" id="edit_benef_wrap">
                    <label>مبالغ المستفيدين</label>
                    <div id="edit_benef_list"></div>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="addEditBenefRow('')">+ مستفيد</button>
                </div>
                <div class="form-group full"><label>عنوان الشركة</label><input type="text" name="adresse_assurance" id="edit_adresse_assurance"></div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="closeEditModal()">إلغاء</button>
                <button type="submit" class="btn btn-success">💾 حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>


<script src="{{ asset('js/dossier-calc.js') }}"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const BASE = "{{ url('/') }}";

function filterDossierTable(q) {
    q = (q || '').toLowerCase().trim();
    document.querySelectorAll('.dossier-row').forEach(function (tr) {
        const hay = (tr.getAttribute('data-search') || '');
        tr.style.display = !q || hay.includes(q) ? '' : 'none';
    });
}

function fmt(n) { return (Math.round(parseFloat(n) * 100) / 100).toFixed(2); }

function fillBreakdownTable(b) {
    const tb = document.getElementById('breakdownBody');
    const rows = [
        ['المبلغ الأصلي ', b.montant_affiche_original],
        //['الأساس للرسم القضائي', b.montant_pour_rasm],
        ['الرسم القضائي', b.rasm_qadai],
        ['حقوق المرافعة', b.rusum_murafaa],
        ['رسم البحث', b.rasm_bahth],
        ['الخبرة', b.expertise],
        ['مصاريف الجنازة', b.masarif_janaza],
        ['المجموع', b.total],
    ];
    tb.innerHTML = rows.map(function (r) {
        return '<tr><th style="text-align:right;padding:0.35rem">' + r[0] + '</th><td style="text-align:left">' + fmt(r[1]) + '</td></tr>';
    }).join('');
}

function openBreakdownPreview(btn) {
    if (!window.DossierCalc) return;
    var payload = JSON.parse(btn.getAttribute('data-payload'));
    fillBreakdownTable(window.DossierCalc.buildBreakdown(payload));
    document.getElementById('breakdownModal').classList.add('open');
}

function openBreakdownServer(id) {
    fetch(BASE + '/dossiers/' + id + '/breakdown', { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (b) { fillBreakdownTable(b); document.getElementById('breakdownModal').classList.add('open'); });
}

function handleFileSelect(input) {
    const file = input.files[0];
    const zone = document.getElementById('uploadZone');
    const icon = document.getElementById('uploadIcon');
    const title = document.getElementById('uploadTitle');
    const sub = document.getElementById('fileName');

    if (file) {
        // Affichage du feedback
        zone.style.borderColor = 'var(--success)';
        zone.style.background = 'rgba(34,197,94,0.08)';
        icon.textContent = '✅';
        title.textContent = file.name;
        title.style.color = 'var(--success)';
        // Taille du fichier
        const size = file.size < 1024 * 1024
            ? (file.size / 1024).toFixed(1) + ' KB'
            : (file.size / (1024 * 1024)).toFixed(1) + ' MB';
        sub.textContent = '📎 ' + size + ' — جاهز للتحليل';
        sub.style.color = 'var(--success)';
    } else {
        // Reset
        zone.style.borderColor = '';
        zone.style.background = '';
        icon.textContent = '📤';
        title.textContent = 'اسحب الملف هنا أو انقر للاختيار';
        title.style.color = '';
        sub.textContent = 'PDF أو JPG أو PNG — بحد أقصى 80 ميغابايت';
        sub.style.color = '';
    }
}
function clearCalcBenefList() { var box = document.getElementById('calc_benef_list'); if (box) box.innerHTML = ''; }

function addCalcBenefRow(val) {
    val = val === undefined || val === null ? '' : val;
    var box = document.getElementById('calc_benef_list');
    if (!box) return;
    var d = document.createElement('div');
    d.style.marginBottom = '0.35rem';
    d.innerHTML = '<input type="number" step="0.01" min="0" name="beneficiaires[][montant]" value="' + String(val).replace(/"/g, '&quot;') + '" oninput="updateCalcPreview()">';
    box.appendChild(d);
}

function toggleCalcExtra() {
    const t = document.getElementById('calc_type_cas').value;
    document.getElementById('wrap_rasemal').style.display = t === 'masdar_total_taawidat' ? '' : 'none';
    document.getElementById('wrap_taawidat').style.display = t === 'masdar_total_taawidat' ? '' : 'none';
    const waf = t === 'wafaya_irad_omri' || t === 'wafaya_ras_mal';
    document.getElementById('wrap_janaza').style.display = waf ? '' : 'none';
    document.getElementById('wrap_benef').style.display = waf ? '' : 'none';
    if (!waf) { clearCalcBenefList(); }
    else if (document.getElementById('calc_benef_list').children.length === 0) { addCalcBenefRow(''); addCalcBenefRow(''); }
    updateCalcPreview();
}

function readCalcPayloadFromForm() {
    var ben = [];
    document.querySelectorAll('#calc_benef_list input[name^="beneficiaires"]').forEach(function (inp) {
        ben.push({ montant: parseFloat(inp.value) || 0 });
    });
    return {
        type_cas: document.getElementById('calc_type_cas').value,
        montant_initial: parseFloat(document.getElementById('calc_montant_initial').value) || 0,
        montant_rasemal_ijmali: parseFloat(document.getElementById('calc_montant_rasemal_ijmali').value) || 0,
        montant_taawidat_youmiya: parseFloat(document.getElementById('calc_montant_taawidat_youmiya').value) || 0,
        masarif_janaza: parseFloat(document.getElementById('calc_masarif_janaza').value) || 0,
        expertise: parseFloat(document.getElementById('calc_expertise').value) || 0,
        beneficiaires_json: ben,
    };
}

function updateCalcPreview() {
    if (!window.DossierCalc) return;
    var b = window.DossierCalc.buildBreakdown(readCalcPayloadFromForm());
    document.getElementById('calcPreviewTotal').textContent = fmt(b.total);
}

function openCalcModal(id, el) {
    id = parseInt(id);
    var payload = JSON.parse(el.getAttribute('data-payload') || '{}');
    document.getElementById('calcForm').action = BASE + '/calculate/' + id;
    document.getElementById('calc_type_cas').value = payload.type_cas || 'autre';
    document.getElementById('calc_montant_initial').value = payload.montant_initial ?? '';
    document.getElementById('calc_expertise').value = payload.expertise ?? '';
    document.getElementById('calc_montant_rasemal_ijmali').value = payload.montant_rasemal_ijmali ?? '';
    document.getElementById('calc_montant_taawidat_youmiya').value = payload.montant_taawidat_youmiya ?? '';
    document.getElementById('calc_masarif_janaza').value = payload.masarif_janaza ?? '';
    document.getElementById('calc_type_malaf').value = payload.type_malaf || '';
    clearCalcBenefList();
    (payload.beneficiaires_json || []).forEach(function (row) { addCalcBenefRow(row && row.montant != null ? row.montant : ''); });
    toggleCalcExtra(); updateCalcPreview();
    document.getElementById('calcModal').classList.add('open');
}

function previewCalcFromForm() {
    if (!window.DossierCalc) return;
    fillBreakdownTable(window.DossierCalc.buildBreakdown(readCalcPayloadFromForm()));
    document.getElementById('breakdownModal').classList.add('open');
}

function openManualModal() { document.getElementById('manualModal').classList.add('open'); }
function clearEditBenefList() { var box = document.getElementById('edit_benef_list'); if (box) box.innerHTML = ''; }

function addEditBenefRow(val) {
    val = val === undefined || val === null ? '' : val;
    var box = document.getElementById('edit_benef_list');
    if (!box) return;
    var d = document.createElement('div');
    d.style.marginBottom = '0.35rem';
    d.innerHTML = '<input type="number" step="0.01" min="0" name="beneficiaires[][montant]" value="' + String(val).replace(/"/g, '&quot;') + '">';
    box.appendChild(d);
}

function openEditModalFromButton(el) {
    const id = el.dataset.id;
    document.getElementById('editForm').action = BASE + '/dossiers/' + id;
    document.getElementById('edit_numero_dossier').value = el.dataset.numero_dossier || '';
    document.getElementById('edit_numero_jugement').value = el.dataset.numero_jugement || '';
    document.getElementById('edit_date_jugement').value = el.dataset.date_jugement || '';
    document.getElementById('edit_nom_victime').value = el.dataset.nom_victime || '';
    document.getElementById('edit_nom_assurance').value = el.dataset.nom_assurance || '';
    document.getElementById('edit_adresse_assurance').value = el.dataset.adresse_assurance || '';
    document.getElementById('edit_montant_initial').value = el.dataset.montant_initial || '';
    document.getElementById('edit_expertise').value = el.dataset.expertise || '';
    document.getElementById('edit_type_cas').value = el.dataset.type_cas || '';
    document.getElementById('edit_type_malaf').value = el.dataset.type_malaf || '';
    document.getElementById('edit_montant_rasemal_ijmali').value = el.dataset.montant_rasemal_ijmali || '';
    document.getElementById('edit_montant_taawidat_youmiya').value = el.dataset.montant_taawidat_youmiya || '';
    document.getElementById('edit_masarif_janaza').value = el.dataset.masarif_janaza || '';
    clearEditBenefList();
    var benRaw = el.getAttribute('data-beneficiaires');
    if (benRaw) { try { var arr = JSON.parse(benRaw); if (Array.isArray(arr)) arr.forEach(function (row) { addEditBenefRow(row && row.montant != null ? row.montant : ''); }); } catch(e){} }
    document.getElementById('editModal').classList.add('open');
}

function closeEditModal() { document.getElementById('editModal').classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
    overlay.addEventListener('click', function (e) { if (e.target === this) this.classList.remove('open'); });
});

function toggleSaveBtn(id, checked) {
    const btn = document.getElementById('savebtn-' + id);
    if (checked) btn.classList.add('visible');
    else {
        btn.classList.remove('visible');
        fetch(BASE + '/dossiers/' + id + '/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ saved: false })
        });
    }
}

function saveDossier(id) {
    fetch(BASE + '/dossiers/' + id + '/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ saved: true })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.success) {
            var btn = document.getElementById('savebtn-' + id);
            btn.textContent = '✅';
            setTimeout(function () { btn.textContent = '💾'; }, 1500);
        } else if (data.message) { alert(data.message); }
    });
}
</script>
</body>
</html>