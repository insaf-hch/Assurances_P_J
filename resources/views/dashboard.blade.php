<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    /* 🎨 BROWN THEME */
  --accent: #7B4F2C;     /* brown plus élégant */
    --accent2: #9C6B4A;    /* hover */

    --brown-light: #E3D5CA;
    --brown-bg: #F8F3EE;

    --success: #22c55e;
    --warning: #f59e0b;
    --error: #ef4444;

    --gold: #eab308;
}
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            direction: rtl;
        }

        .layout { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 260px;
            background: var(--surface);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
        }

        .sidebar-logo {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), #0369a1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }

        .logo-text { font-size: 0.95rem; font-weight: 700; line-height: 1.2; }
        .logo-sub { font-size: 0.7rem; color: var(--text3); font-weight: 400; }

        .sidebar-nav { padding: 1rem 0; flex: 1; }

        .nav-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text3);
            letter-spacing: 0.1em;
            padding: 0.5rem 1.25rem;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: var(--text2);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.15s;
            border-right: 3px solid transparent;
            margin: 1px 0;
        }

        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active {
            background: rgba(32,105,139,0.1);
            color: var(--accent2);
            border-right-color: var(--accent);
        }

        .nav-icon { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            font-size: 0.75rem;
            color: var(--text3);
        }

        /* ── MAIN ── */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0.875rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-title { font-size: 1rem; font-weight: 600; }
        .topbar-sub { font-size: 0.75rem; color: var(--text3); margin-top: 1px; }
        .topbar-actions { display: flex; gap: 0.75rem; align-items: center; }

        .badge {
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 6px;
            padding: 0.3rem 0.75rem;
            font-size: 0.75rem;
            color: var(--text2);
        }

        .content { padding: 1.5rem 2rem; flex: 1; overflow-y: auto; }

        /* ── STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-icon.blue { background: rgba(14,165,233,0.15); }
        .stat-icon.green { background: rgba(16,185,129,0.15); }
        .stat-icon.gold { background: rgba(212,168,67,0.15); }
        .stat-icon.purple { background: rgba(139,92,246,0.15); }

        .stat-val { font-size: 1.5rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 0.75rem; color: var(--text3); margin-top: 2px; }

        /* ── PANELS ── */
        .panels-grid { display: flex; flex-direction: column; gap: 1.25rem; }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .panel-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .panel-title-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--accent);
        }

     
        /* ── UPLOAD ── */
.upload-zone {
    border: 1px dashed var(--border2);
    padding: 0.5rem;
    border-radius: 8px;
    max-width: 300px;
    
}

.upload-icon {
    font-size: 0.5rem; /* ⬅️ plus petit */
    margin-bottom: 0.3rem;
}



.upload-sub {
    font-size: 0.7rem;
}
        input[type="file"] { display: none; }

        /* ── FORM ── */
        .manual-form { padding: 0 1.25rem 1.25rem; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.625rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.3rem; }
        .form-group.full { grid-column: 1 / -1; }

        label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text3);
            letter-spacing: 0.02em;
        }

        input, select, textarea {
            background: var(--bg);
            border: 1px solid var(--border2);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: var(--text);
            font-family: 'Cairo', sans-serif;
            font-size: 0.83rem;
            transition: border-color 0.15s;
            width: 100%;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--accent); }
        select option { background: var(--surface2); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1.1rem;
            border: none;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent2); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { filter: brightness(1.1); }
        .btn-warning { background: var(--warning); color: #000; }
        .btn-warning:hover { filter: brightness(1.1); }
        .btn-danger { background: var(--error); color: #fff; }
        .btn-danger:hover { filter: brightness(1.1); }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border2);
            color: var(--text2);
        }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.72rem; }
        .btn-full { width: 100%; justify-content: center; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }

        thead tr { background: var(--surface2); border-bottom: 1px solid var(--border); }

        th {
            padding: 0.65rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text3);
            letter-spacing: 0.04em;
            text-align: right;
            white-space: nowrap;
        }

        td {
            padding: 0.65rem 0.75rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            text-align: right;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(14,165,233,0.02); }
        .td-num { direction: ltr; text-align: left; font-variant-numeric: tabular-nums; }

        /* ── CHIPS ── */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.68rem;
            font-weight: 600;
        }
        .chip-blue { background: rgba(14,165,233,0.15); color: var(--accent2); }
        .chip-green { background: rgba(16,185,129,0.15); color: #34d399; }
        .chip-warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .chip-muted { background: var(--surface2); color: var(--text3); }
        .chip::before { content: '●'; font-size: 0.5rem; }

        /* ── ALERTS ── */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .alert-success { background: rgba(16,185,129,0.1); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
        .alert-error { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.25); }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 3rem 1.5rem; color: var(--text3); }
        .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.5; }
        .empty-title { font-size: 0.9rem; font-weight: 600; color: var(--text2); margin-bottom: 0.25rem; }

        /* ── ACTIONS ROW ── */
        .actions-row { display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap; }
        .calc-inline { display: flex; gap: 0.3rem; align-items: center; }
        .calc-inline select { width: auto; font-size: 0.72rem; padding: 0.3rem 0.5rem; }
        .calc-inline input { width: 70px; font-size: 0.72rem; padding: 0.3rem 0.5rem; }

        /* ── ACTION BUTTONS GROUP ── */
        .action-group {
            display: flex;
            gap: 0.3rem;
            align-items: center;
            border-top: 1px solid var(--border);
            padding-top: 0.4rem;
            margin-top: 0.4rem;
        }

        /* ── SAVE CHECKBOX ── */
        .save-checkbox-cell { text-align: center !important; }
        .save-checkbox-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }
        .custom-checkbox {
            width: 18px; height: 18px;
            accent-color: var(--success);
            cursor: pointer;
        }
        .save-btn-inline {
            font-size: 0.62rem;
            padding: 0.2rem 0.4rem;
            background: var(--success);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            display: none;
        }
        .save-btn-inline.visible { display: inline-flex; }

        /* ── EDIT ROW (inline) ── */
        tr.editing td { background: rgba(14,165,233,0.05); }
        .edit-input {
            background: var(--surface2);
            border: 1px solid var(--accent);
            border-radius: 6px;
            padding: 0.25rem 0.4rem;
            color: var(--text);
            font-family: 'Cairo', sans-serif;
            font-size: 0.78rem;
            width: 100%;
            min-width: 80px;
        }

        /* ── MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }

        .modal {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 16px;
            padding: 1.5rem;
            width: 480px;
            max-width: 95vw;
            animation: modalIn 0.2s ease;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        .modal-title { font-size: 1rem; font-weight: 700; }
        .modal-close {
            background: none; border: none; color: var(--text3);
            font-size: 1.3rem; cursor: pointer; line-height: 1;
        }
        .modal-close:hover { color: var(--text); }
        .modal-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .modal-form-grid .full { grid-column: 1/-1; }
        .modal-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; justify-content: flex-end; }

        /* ── CONFIRM MODAL ── */
        .confirm-modal { width: 360px; text-align: center; }
        .confirm-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .confirm-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.4rem; }
        .confirm-sub { font-size: 0.82rem; color: var(--text3); margin-bottom: 1.25rem; }
        .confirm-actions { display: flex; gap: 0.75rem; justify-content: center; }

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-top: 1px solid var(--border);
            font-size: 0.8rem;
            color: var(--text3);
        }
        .pagination a { color: var(--accent); }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }


.upload-zone:hover {
    border-color: var(--accent);
    background: rgba(139,94,60,0.08);
}

/* Réduction taille contenu */
.upload-icon {
    font-size: 1.5rem;
    margin-bottom: 0.3rem;
}

.upload-title {
    font-size: 0.7rem;
    font-weight: 500;
}

.upload-sub {
    font-size: 0.65rem;
}
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
            <div class="nav-item active"><span class="nav-icon">🗂️</span> الملفات والدوسيرات</div>
            <div class="nav-item"><span class="nav-icon">📊</span> الإحصائيات</div>
            <div class="nav-item"><span class="nav-icon">📄</span> الوثائق المُنتجة</div>
            <div class="nav-label" style="margin-top:1rem;">الإعدادات</div>
            <div class="nav-item"><span class="nav-icon">⚙️</span> الإعدادات العامة</div>
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
                <span class="badge">🟢 النظام يعمل</span>
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

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">🗂️</div>
                    <div>
                        <div class="stat-val">{{ $dossiers->total() }}</div>
                        <div class="stat-label">مجموع الملفات</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">✅</div>
                    <div>
                        <div class="stat-val">{{ $dossiers->getCollection()->where('calcul', '!=', null)->count() }}</div>
                        <div class="stat-label">محسوبة</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold">⏳</div>
                    <div>
                        <div class="stat-val">{{ $dossiers->getCollection()->where('calcul', null)->count() }}</div>
                        <div class="stat-label">في الانتظار</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">📄</div>
                    <div>
                        <div class="stat-val">{{ $dossiers->getCollection()->where('calcul', '!=', null)->count() }}</div>
                        <div class="stat-label">وثائق جاهزة</div>
                    </div>
                </div>
            </div>

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
                        <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                            <div class="upload-icon">📤</div>
                            <div class="upload-title">اسحب الملف هنا أو انقر للاختيار</div>
                            <div class="upload-sub" id="fileName">PDF أو JPG أو PNG — بحد أقصى 80 ميغابايت</div>
                            <input type="file" name="document" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" required
                                onchange="document.getElementById('fileName').textContent = this.files[0]?.name || 'لم يتم اختيار ملف'">
                        </div>
                        <div style="padding: 0 1.25rem 1.25rem; display:flex; justify-content:flex-end;">
    <button type="submit" class="btn btn-primary">تحليل الملف</button>
</div>

                    </form>
                </div>

                <!-- TABLE -->
              <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">

    <!-- 🔍 SEARCH LEFT -->
    <div>
        <input type="text" placeholder="بحث..."
               style="
               padding:6px 10px;
               border:1px solid var(--border2);
               border-radius:6px;
               font-size:0.75rem;
               width:180px;">
    </div>

    <!-- TITLE CENTER -->
    <div class="panel-title">
        <div class="panel-title-dot"></div>
        قائمة الملفات
    </div>

    <!-- COUNT RIGHT -->
    <span class="badge">{{ $dossiers->total() }} ملف</span>

</div>

                    @if($dossiers->isEmpty())
                        <div class="empty">
                            <div class="empty-icon">📂</div>
                            <div class="empty-title">لا توجد ملفات بعد</div>
                            قم برفع ملف PDF أو إضافة ملف يدوياً
                        </div>
                    @else
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>حفظ</th>
                                        <th>#</th>
                                        <th>رقم الملف</th>
                                        <th>شركة التأمين</th>
                                        <th>المبلغ الأصلي</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                        <th>الأفعال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dossiers as $d)
                                    <tr id="row-{{ $d->id }}">

                                        {{-- ✅ COLONNE SAUVEGARDER --}}
                                        <td class="save-checkbox-cell">
                                            <div class="save-checkbox-wrap">
                                                <input type="checkbox"
                                                    class="custom-checkbox"
                                                    id="chk-{{ $d->id }}"
                                                    onchange="toggleSaveBtn({{ $d->id }}, this.checked)"
                                                    {{ $d->saved ? 'checked' : '' }}>
                                                <button class="save-btn-inline {{ $d->saved ? 'visible' : '' }}"
                                                    id="savebtn-{{ $d->id }}"
                                                    onclick="saveDossier({{ $d->id }})">
                                                    💾
                                                </button>
                                            </div>
                                        </td>

                                        <td class="td-num" style="color:var(--text3)">{{ $d->id }}</td>

                                        <td>
                                            <strong>{{ $d->numero_dossier ?: '—' }}</strong>
                                            @if($d->date_jugement)
                                                <div style="font-size:0.7rem;color:var(--text3)">
                                                    {{ $d->date_jugement->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if($d->nom_assurance_normalise)
                                                <span class="chip chip-blue">{{ $d->nom_assurance_normalise }}</span>
                                            @else
                                                <span style="color:var(--text3)">—</span>
                                            @endif
                                        </td>

                                        <td class="td-num">
                                            {{ number_format((float) $d->montant_initial, 2, '.', ' ') }}
                                            <span style="color:var(--text3);font-size:0.7rem">دره</span>
                                        </td>

                                        <td>
                                            @if($d->calcul)
                                                <span class="chip chip-green">محسوب</span>
                                                <div class="td-num" style="font-size:0.7rem;color:var(--success);margin-top:2px">
                                                    {{ number_format((float) $d->calcul->total, 2, '.', ' ') }} دره
                                                </div>
                                            @else
                                                <span class="chip chip-warning">في الانتظار</span>
                                            @endif
                                        </td>

                                        {{-- ⚙️ CALCUL --}}
                                        <td>
                                            <form method="post" action="{{ route('calculate', $d) }}">
                                                @csrf
                                                <div class="calc-inline">
                                                    <select name="type_cas" required>
                                                        <option value="" disabled {{ $d->type_cas ? '' : 'selected' }}>النوع</option>
                                                        <option value="irad_omri" @selected($d->type_cas === 'irad_omri')>إيراد عمري ×10</option>
                                                        <option value="irad_omri_ras_mal" @selected($d->type_cas === 'irad_omri_ras_mal')>إيراد رأس مال</option>
                                                        <option value="gharama_ijbariya" @selected($d->type_cas === 'gharama_ijbariya')>غرامة إجبارية</option>
                                                        <option value="autre" @selected($d->type_cas === 'autre')>حالة 4</option>
                                                    </select>
                                                    <input type="number" name="expertise" step="0.01" min="0"
                                                        placeholder="خبرة"
                                                        value="{{ old('expertise', $d->expertise) }}">
                                                    <button type="submit" class="btn btn-primary btn-sm">حساب</button>
                                                </div>
                                                @if($d->calcul)
                                                    <div style="margin-top:0.3rem;">
                                                        <a class="btn btn-ghost btn-sm" href="{{ route('generate-word', $d->id) }}">📄 وثيقة</a>
                                                    </div>
                                                @endif
                                            </form>
                                        </td>

                                        {{-- 🔧 ACTIONS : EDIT + DELETE --}}
                                        <td>
                                            <div class="actions-row">
                                                {{-- Bouton Modifier --}}
                                                <button class="btn btn-warning btn-sm"
                                                   onclick="openEditModalFromButton(this)"
                                                        data-id="{{ $d->id }}"
                                                        data-numero_dossier="{{ $d->numero_dossier }}"
                                                        data-numero_jugement="{{ $d->numero_jugement }}"
                                                        data-date_jugement="{{ $d->date_jugement?->format('Y-m-d') }}"
                                                        data-nom_victime="{{ $d->nom_victime }}"
                                                        data-nom_assurance="{{ $d->nom_assurance }}"
                                                        data-adresse_assurance="{{ $d->adresse_assurance }}"
                                                        data-montant_initial="{{ $d->montant_initial }}"
                                                        data-expertise="{{ $d->expertise }}"
                                                        data-type_cas="{{ $d->type_cas }}"
                                                    ✏️ تعديل
                                                </button>

                                                {{-- Bouton Supprimer --}}
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="openDeleteConfirm({{ $d->id }}, '{{ addslashes($d->numero_dossier ?: '#'.$d->id) }}')">
                                                    🗑️ حذف
                                                </button>
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

                <!-- AJOUT MANUEL -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <div class="panel-title-dot" style="background:var(--gold)"></div>
                            إضافة ملف يدوياً
                        </div>
                    </div>
                    <div class="manual-form">
                        <form method="post" action="{{ route('manual.store') }}">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>رقم الملف</label>
                                    <input type="text" name="numero_dossier" placeholder="376/1502/2023">
                                </div>
                                <div class="form-group">
                                    <label>رقم الحكم</label>
                                    <input type="text" name="numero_jugement" placeholder="149">
                                </div>
                                <div class="form-group">
                                    <label>تاريخ القرار</label>
                                    <input type="date" name="date_jugement">
                                </div>
                                <div class="form-group">
                                    <label>شركة التأمين</label>
                                    <input type="text" name="nom_assurance" placeholder="الوفاء">
                                </div>
                                <div class="form-group full">
                                    <label>عنوان الشركة</label>
                                    <input type="text" name="adresse_assurance" placeholder="01 شارع عبد المومن الدار البيضاء">
                                </div>
                                <div class="form-group">
                                    <label>المبلغ الأصلي (درهم)</label>
                                    <input type="number" name="montant_initial" step="0.01" min="0" placeholder="23372.71">
                                </div>
                                <div class="form-group">
                                    <label>الخبرة / تسبيقات الخزينة</label>
                                    <input type="number" name="expertise" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label>نوع الحالة</label>
                                    <select name="type_cas">
                                        <option value="">اختر النوع</option>
                                        <option value="irad_omri">1 — إيراد عمري (×10)</option>
                                        <option value="irad_omri_ras_mal">2 — إيراد عمري رأس مال</option>
                                        <option value="gharama_ijbariya">3 — غرامة إجبارية</option>
                                        <option value="autre">4 — حالة مستقبلية</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>اسم المصاب</label>
                                    <input type="text" name="nom_victime" placeholder="الاسم الكامل">
                                </div>
                            </div>
                            <div style="margin-top:0.875rem;">
                                <button type="submit" class="btn btn-success btn-full">✚ تسجيل الملف يدوياً</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- /panels-grid -->
        </div><!-- /content -->
    </main>
</div>

<!-- ══════════════════════════════════════════════
     MODAL MODIFICATION
══════════════════════════════════════════════ -->
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
                <div class="form-group">
                    <label>رقم الملف</label>
                    <input type="text" name="numero_dossier" id="edit_numero_dossier">
                </div>
                <div class="form-group">
                    <label>رقم الحكم</label>
                    <input type="text" name="numero_jugement" id="edit_numero_jugement">
                </div>
                <div class="form-group">
                    <label>تاريخ القرار</label>
                    <input type="date" name="date_jugement" id="edit_date_jugement">
                </div>
                <div class="form-group">
                    <label>اسم المصاب</label>
                    <input type="text" name="nom_victime" id="edit_nom_victime">
                </div>
                <div class="form-group">
                    <label>شركة التأمين</label>
                    <input type="text" name="nom_assurance" id="edit_nom_assurance">
                </div>
                <div class="form-group">
                    <label>المبلغ الأصلي</label>
                    <input type="number" step="0.01" name="montant_initial" id="edit_montant_initial">
                </div>
                <div class="form-group">
                    <label>الخبرة</label>
                    <input type="number" step="0.01" name="expertise" id="edit_expertise">
                </div>
                <div class="form-group">
                    <label>نوع الحالة</label>
                    <select name="type_cas" id="edit_type_cas">
                        <option value="">اختر النوع</option>
                        <option value="irad_omri">إيراد عمري (×10)</option>
                        <option value="irad_omri_ras_mal">إيراد عمري رأس مال</option>
                        <option value="gharama_ijbariya">غرامة إجبارية</option>
                        <option value="autre">حالة 4</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label>عنوان الشركة</label>
                    <input type="text" name="adresse_assurance" id="edit_adresse_assurance">
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="closeEditModal()">إلغاء</button>
                <button type="submit" class="btn btn-success">💾 حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL CONFIRMATION SUPPRESSION
══════════════════════════════════════════════ -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="confirm-icon">⚠️</div>
        <div class="confirm-title">تأكيد الحذف</div>
        <div class="confirm-sub" id="deleteConfirmText">هل أنت متأكد من حذف هذا الملف؟</div>
        <div class="confirm-actions">
            <button class="btn btn-ghost" onclick="closeDeleteModal()">إلغاء</button>
            <form id="deleteForm" method="post" action="" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ نعم، احذف</button>
            </form>
        </div>
    </div>
</div>

<script>
// ── EDIT MODAL ──
function openEditModalFromButton(el) {
    const id = el.dataset.id;

    document.getElementById('editForm').action = '/dossiers/' + id;

    document.getElementById('edit_numero_dossier').value = el.dataset.numero_dossier || '';
    document.getElementById('edit_numero_jugement').value = el.dataset.numero_jugement || '';
    document.getElementById('edit_date_jugement').value = el.dataset.date_jugement || '';
    document.getElementById('edit_nom_victime').value = el.dataset.nom_victime || '';
    document.getElementById('edit_nom_assurance').value = el.dataset.nom_assurance || '';
    document.getElementById('edit_adresse_assurance').value = el.dataset.adresse_assurance || '';
    document.getElementById('edit_montant_initial').value = el.dataset.montant_initial || '';
    document.getElementById('edit_expertise').value = el.dataset.expertise || '';
    document.getElementById('edit_type_cas').value = el.dataset.type_cas || '';

    document.getElementById('editModal').classList.add('open');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('open');
}

// ── DELETE MODAL ──
function openDeleteConfirm(id, label) {
    document.getElementById('deleteForm').action = '/dossiers/' + id;
    document.getElementById('deleteConfirmText').textContent =
        'هل أنت متأكد من حذف الملف رقم "' + label + '"؟ لا يمكن التراجع عن هذا الإجراء.';
    document.getElementById('deleteModal').classList.add('open');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
        }
    });
});

// ── SAVE CHECKBOX ──
function toggleSaveBtn(id, checked) {
    const btn = document.getElementById('savebtn-' + id);
    if (checked) {
        btn.classList.add('visible');
    } else {
        btn.classList.remove('visible');
        // Si décoché → envoyer "non sauvegardé"
        fetch('/dossiers/' + id + '/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ saved: false })
        });
    }
}

function saveDossier(id) {
    fetch('/dossiers/' + id + '/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ saved: true })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById('savebtn-' + id);
            btn.textContent = '✅';
            setTimeout(() => { btn.textContent = '💾'; }, 1500);
        }
    })
    .catch(() => {});
}
</script>

</body>
</html>