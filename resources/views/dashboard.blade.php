<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نظام معالجة ملفات التأمين</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
:root {
    /* Backgrounds */
    --bg:         #ffffff;
    --surface:    #FFFFFF;
    --surface2:   #ffffff;

    /* Borders */
    --border:     #E4D9CE;
    --border2:    #CDBFB0;

    /* Text */
    --text:       #000000;
    --text2:      #000000;
    --text3:      #000000;

    /* Brown — accent principal */
    --brown:      #7B4F2C;
    --brown2:     #9C6B4A;
    --brown-soft: #F5ECE3;
    --brown-light:#E8D5C2;

    /* Sky blue — accent secondaire */
    --sky:        #76c4d8;
    --sky2:       #38BDF8;
    --sky-soft:   #E0F6FD;
    --sky-dark:   #0369A1;

    /* Sémantiques */
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

/* ─── PANELS ──────────────────────────────────────────────── */
.panels-grid { display: flex; flex-direction: column; gap: 1.1rem; }

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

/* ─── UPLOAD ROW ──────────────────────────────────────────── */
.upload-row {
    padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 0.875rem;
}
.upload-zone {
    flex: 1; min-width: 0;
    border: 1.5px dashed var(--border2);
    padding: 0.7rem 1rem;
    border-radius: 8px;
    cursor: pointer; transition: all 0.18s;
    display: flex; align-items: center; gap: 0.75rem;
    background: var(--bg);
}
.upload-zone:hover { border-color: var(--sky); background: var(--sky-soft); }
.upload-zone-icon { font-size: 1.2rem; flex-shrink: 0; }
.upload-zone-text { min-width: 0; }
.upload-title { font-size: 0.8rem; font-weight: 600; color: var(--text); }
.upload-sub   { font-size: 0.68rem; color: var(--text3); margin-top: 1px; }
input[type="file"] { display: none; }

/* ─── FORM ────────────────────────────────────────────────── */
.modal-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.7rem; }
.form-group { display: flex; flex-direction: column; gap: 0.28rem; }
.form-group.full { grid-column: 1 / -1; }
label { font-size: 0.7rem; font-weight: 600; color: var(--text3); letter-spacing: 0.02em; }
input, select, textarea {
    background: var(--bg);
    border: 1px solid var(--border2);
    border-radius: 7px;
    padding: 0.48rem 0.72rem;
    color: var(--text);
    font-family: 'IBM Plex Sans Arabic', sans-serif;
    font-size: 0.82rem;
    transition: border-color 0.13s;
    width: 100%;
}
input:focus, select:focus, textarea:focus { outline: none; border-color: var(--sky); box-shadow: 0 0 0 3px rgba(14,165,201,0.1); }
select option { background: var(--surface); }

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
.btn-success  { background: var(--success); color: #fff; }
.btn-success:hover { filter: brightness(1.1); }
.btn-warning  { background: var(--brown); color: #fff; }
.btn-warning:hover { background: var(--brown2); }
.btn-danger   { background: var(--error); color: #fff; }
.btn-danger:hover { filter: brightness(1.1); }
.btn-ghost {
    background: transparent;
    border: 1px solid var(--border2);
    color: var(--text2);
}
.btn-ghost:hover { border-color: var(--sky); color: var(--sky-dark); }
.btn-sm   { padding: 0.28rem 0.6rem; font-size: 0.71rem; }
.btn-full { width: 100%; justify-content: center; }

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

/* ─── CHIPS ───────────────────────────────────────────────── */
.chip {
    display: inline-flex; align-items: center; gap: 0.28rem;
    padding: 0.18rem 0.6rem;
    border-radius: 20px;
    font-size: 0.67rem; font-weight: 600;
}
.chip::before { content: '●'; font-size: 0.45rem; }
.chip-blue    { background: var(--sky-soft);    color: var(--sky-dark); }
.chip-green   { background: var(--success-soft);color: var(--success);  }
.chip-warning { background: var(--warning-soft);color: var(--warning);  }
.chip-muted   { background: var(--surface2);    color: var(--text3);    }

/* ─── ALERTS ──────────────────────────────────────────────── */
.alert {
    padding: 0.72rem 1rem; border-radius: 9px;
    margin-bottom: 0.875rem; font-size: 0.83rem;
    display: flex; align-items: flex-start; gap: 0.5rem;
}
.alert-success { background: var(--success-soft); color: var(--success); border: 1px solid #C0DD97; }
.alert-error   { background: var(--error-soft);   color: var(--error);   border: 1px solid #FCA5A5; }

/* ─── EMPTY ───────────────────────────────────────────────── */
.empty { text-align: center; padding: 2.5rem 1.5rem; color: var(--text3); }
.empty-icon  { font-size: 2.2rem; margin-bottom: 0.6rem; opacity: 0.4; }
.empty-title { font-size: 0.88rem; font-weight: 600; color: var(--text2); margin-bottom: 0.2rem; }

/* ─── ACTIONS ROW ─────────────────────────────────────────── */
.actions-row { display: flex; gap: 0.3rem; align-items: center; flex-wrap: wrap; }

/* ─── SAVE CHECKBOX ───────────────────────────────────────── */
.save-checkbox-cell { text-align: center !important; }
.save-checkbox-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; }
.custom-checkbox { width: 16px; height: 16px; accent-color: var(--sky); cursor: pointer; }
.save-btn-inline {
    font-size: 0.6rem; padding: 0.18rem 0.38rem;
    background: var(--sky); color: #fff;
    border: none; border-radius: 4px; cursor: pointer;
    font-family: 'IBM Plex Sans Arabic', sans-serif; display: none;
}
.save-btn-inline.visible { display: inline-flex; }

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
    width: 550px; max-width: 95vw; max-height: 92vh; overflow-y: auto;
    animation: modalIn 0.18s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.96) translateY(-8px); }
    to   { opacity: 1; transform: scale(1)    translateY(0);    }
}
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.1rem; }
.modal-title  { font-size: 0.95rem; font-weight: 700; color: var(--brown); }
.modal-close  { background: none; border: none; color: var(--text3); font-size: 1.2rem; cursor: pointer; }
.modal-close:hover { color: var(--brown); }
.modal-actions { display: flex; gap: 0.65rem; margin-top: 1.1rem; justify-content: flex-end; }

/* ─── PAGINATION ──────────────────────────────────────────── */
.pagination {
    display: flex; gap: 0.5rem; align-items: center; justify-content: center;
    padding: 0.875rem; border-top: 1px solid var(--border);
    font-size: 0.78rem; color: var(--text3);
}
.pagination a { color: var(--sky-dark); text-decoration: none; font-weight: 600; }

/* ─── SCROLLBAR ───────────────────────────────────────────── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }

/* ─── NEW FIELDS SECTION ─────────────────────────────────── */
.section-divider {
    grid-column: 1 / -1;
    border-top: 1px dashed var(--border2);
    margin: 0.5rem 0 0.25rem;
    padding-top: 0.5rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--brown);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.section-divider span {
    background: var(--brown-soft);
    padding: 0.1rem 0.5rem;
    border-radius: 16px;
}
    </style>
</head>
<body>
<div class="layout">

    <!-- ═══ SIDEBAR ═══════════════════════════════════════════ -->
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
            <a href="{{ url('/dashboard') }}" class="nav-item active">
                <span class="nav-icon">🗂️</span> الملفات
            </a>
            <a href="{{ route('wathaiq.index') }}" class="nav-item">
                <span class="nav-icon">📄</span> الوثائق المُنتجة
            </a>
        </nav>
        <div style="padding: 0.5rem 1rem; margin-bottom: 0.25rem;">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.62rem 1rem;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 7px;
            color: rgba(255,255,255,0.6);
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            font-size: 0.83rem;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.13s;
        "
        onmouseover="this.style.background='rgba(231, 30, 30, 0.25)';this.style.color='#FCA5A5';this.style.borderColor='rgba(185,28,28,0.4)'"
        onmouseout="this.style.background='transparent';this.style.color='rgba(255,255,255,0.6)';this.style.borderColor='rgba(255,255,255,0.15)'">
            <span style="font-size:1rem;width:20px;text-align:center;"></span>
            تسجيل الخروج
        </button>
    </form>
</div>


        <div class="sidebar-footer">نظام معالجة حوادث الشغل </div>
    </aside>

    <!-- ═══ MAIN ═══════════════════════════════════════════════ -->
    <main class="main">

        <!-- TOPBAR -->
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
<div id="toast-success" style="
    position:fixed;top:1.2rem;left:1.2rem;z-index:9999;
    background:var(--success-soft);border:1px solid #C0DD97;color:var(--success);
    padding:0.75rem 1.2rem;border-radius:10px;font-size:0.83rem;font-weight:600;
    display:flex;align-items:center;gap:0.5rem;
    box-shadow:0 4px 16px rgba(91,128,97,0.18);
    animation:slideInLeft 0.3s ease;
">
    ✅ {{ session('success') }}
</div>
<style>
@keyframes slideInLeft {
    from { opacity:0; transform:translateX(-30px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes slideOutLeft {
    from { opacity:1; transform:translateX(0); }
    to   { opacity:0; transform:translateX(-30px); }
}
</style>
<script>
setTimeout(function() {
    var t = document.getElementById('toast-success');
    if (t) {
        t.style.animation = 'slideOutLeft 0.3s ease forwards';
        setTimeout(function(){ t.remove(); }, 300);
    }
}, 9000);
</script>
@endif

@if($errors->any())
<div id="toast-error" style="
    position:fixed;top:1.2rem;left:1.2rem;z-index:9999;
    background:var(--error-soft);border:1px solid #FCA5A5;color:var(--error);
    padding:0.75rem 1.2rem;border-radius:10px;font-size:0.83rem;font-weight:600;
    display:flex;align-items:center;gap:0.5rem;
    box-shadow:0 4px 16px rgba(185,28,28,0.13);
    animation:slideInLeft 0.3s ease;
">
    ❌ @foreach($errors->all() as $err) {{ $err }} @endforeach
</div>
<script>
setTimeout(function() {
    var t = document.getElementById('toast-error');
    if (t) {
        t.style.animation = 'slideOutLeft 0.3s ease forwards';
        setTimeout(function(){ t.remove(); }, 300);
    }
}, 9000);
</script>
@endif

            <div class="panels-grid">

                <!-- ── UPLOAD PDF ─────────────────────────────── -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <div class="panel-title-dot"></div>
                            رفع ملف PDF
                        </div>
                    </div>
                    <form method="post" action="{{ route('upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-row">
                            <div class="upload-zone" id="uploadZone"
                                 onclick="document.getElementById('fileInput').click()">
                                <div class="upload-zone-icon" id="uploadIcon">📤</div>
                                <div class="upload-zone-text">
                                    <div class="upload-title" id="uploadTitle">اسحب الملف هنا أو انقر للاختيار</div>
                                    <div class="upload-sub"   id="fileName">PDF أو JPG أو PNG — بحد أقصى 80 ميغابايت</div>
                                </div>
                                <input type="file" name="document" id="fileInput"
                                       accept=".pdf,.jpg,.jpeg,.png" required
                                       onchange="handleFileSelect(this)">
                            </div>
                            <button type="submit" class="btn btn-primary" style="white-space:nowrap;flex-shrink:0;">
                                تحليل الملف
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ── TABLE DES DOSSIERS ──────────────────────── -->
                <div class="panel">
                    <div class="panel-header"
                         style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;">
                        <!-- Search -->
                        <div>
                            <input type="text" id="tableSearch" placeholder="بحث..."
                                   oninput="filterDossierTable(this.value)"
                                   style="padding:5px 10px;border:1px solid var(--border2);border-radius:6px;font-size:0.74rem;width:190px;">
                        </div>
                        <!-- Title -->
                        <div class="panel-title" style="flex:1;justify-content:center;">
                            <div class="panel-title-dot"></div>
                            قائمة الملفات
                        </div>
                        <!-- Count + Add -->
                        <div style="display:flex;align-items:center;gap:0.45rem;">
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
                                        <th>رقم الملف</th>
                                        <th>الملف</th>
                                        <th>شركة التأمين</th>
                                        <th>المبلغ </th>
                                        <th>المبلغ المؤدى</th>
                                        <th>ملفات</th>
                                        <th>إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dossiers as $d)
                                    @php
                                        $montantOriginalCalcule = $calculService->getMontantOriginalCalcule($d);
                                      $payload = [
                                                    'type_cas'                => $d->type_cas,
                                                    'type_malaf'              => $d->type_malaf,
                                                    'montant_initial'         => (float) $d->montant_initial,
                                                    'montant_rasemal_ijmali'  => (float) $d->montant_rasemal_ijmali,
                                                    'montant_taawidat_youmiya'=> (float) $d->montant_taawidat_youmiya,
                                                    'masarif_janaza'          => (float) $d->masarif_janaza,
                                                    'expertise'               => (float) $d->expertise,
                                                    'beneficiaires_json'      => $d->beneficiaires_json ?? [],
                                                    // ← ajouter
                                                    'nizaat_darar'            => (float) ($d->nizaat_darar ?? 0),
                                                    'nizaat_ikhtar'           => (float) ($d->nizaat_ikhtar ?? 0),
                                                    'nizaat_otla'             => (float) ($d->nizaat_otla ?? 0),
                                                    'nizaat_aqdamiya'         => (float) ($d->nizaat_aqdamiya ?? 0),
                                                ];
                                        $payloadAttr = json_encode($payload, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT);
                                    @endphp
                                    <tr id="row-{{ $d->id }}" class="dossier-row"
                                        data-search="{{ strtolower(($d->numero_dossier??'').' '.($d->nom_assurance_normalise??'').' '.($d->nom_assurance??'')) }}">

                                        <!-- checkbox save -->
                                        <td class="save-checkbox-cell">
                                            <div class="save-checkbox-wrap">
                                                <input type="checkbox" class="custom-checkbox" id="chk-{{ $d->id }}"
                                                       data-id="{{ $d->id }}"
                                                       onchange="toggleSaveBtn(this.dataset.id, this.checked)"
                                                       {{ $d->saved ? 'checked' : '' }}
                                                       @if(!$d->calcul) disabled title="يجب إجراء الحساب أولاً" @endif>
                                                <button type="button"
                                                        class="save-btn-inline {{ $d->saved ? 'visible' : '' }}"
                                                        id="savebtn-{{ $d->id }}"
                                                        data-id="{{ $d->id }}"
                                                        onclick="saveDossier(this.dataset.id)">💾</button>
                                            </div>
                                        </td>

                                       

                                        <td>
                                            <strong>{{ $d->numero_dossier ?: '—' }}</strong>
                                            @if($d->date_jugement)
                                                <div style="font-size:0.68rem;color:var(--text3);">{{ $d->date_jugement->format('d/m/Y') }}</div>
                                            @endif
                                        </td>

                                        <td style="max-width:130px;font-size:0.77rem;">
                                            @if($d->fichier_pdf)
                                                <a href="{{ asset('storage/'.$d->fichier_pdf) }}" target="_blank"
                                                   style="display:inline-flex;align-items:center;gap:0.28rem;color:var(--sky-dark);text-decoration:none;font-weight:600;">
                                                    📄 ملف
                                                </a>
                                            @else
                                                <span style="color:var(--text3);">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($d->nom_assurance_normalise)
                                                <span class="chip chip-blue">{{ $d->nom_assurance_normalise }}</span>
                                            @else
                                                <span style="color:var(--text3);">—</span>
                                            @endif
                                        </td>

                                        <td dir="ltr" style="text-align:right;font-weight:600;">
                                            {{ number_format((float)$d->montant_initial, 2, '.', ',') }}
                                        </td>

                                        <td>
                                            @if($d->calcul)
                                                <button type="button" class="btn btn-ghost btn-sm"
                                                        data-id="{{ $d->id }}"
                                                        onclick="openBreakdownServer(this.dataset.id)">
                                                    <span dir="ltr">{{ number_format((float)$d->calcul->total, 2, '.', ',') }}</span>
                                                </button>
                                            @elseif($d->type_cas)
                                                <button type="button" class="btn btn-ghost btn-sm"
                                                        data-payload="{{ $payloadAttr }}"
                                                        onclick="openBreakdownPreview(this)">معاينة</button>
                                            @else
                                                <span style="color:var(--text3);">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($d->calcul)
                                                <a class="btn btn-ghost btn-sm" target="_blank" href="{{ route('dossiers.print.istidaa', $d) }}">استدعاء</a>
                                                <a class="btn btn-ghost btn-sm" target="_blank" href="{{ route('dossiers.print.amr', $d) }}">أمر</a>
                                            @else
                                                <span style="color:var(--text3);">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="actions-row">

                                                <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="openEditModalFromButton(this)"
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
                                                        data-montant_taawidat="{{ $d->montant_taawidat ?? 0 }}"
                                                        data-montant_masarif_tibiya="{{ $d->montant_masarif_tibiya ?? 0 }}"
data-nizaat_darar="{{ $d->nizaat_darar ?? 0 }}"
data-nizaat_ikhtar="{{ $d->nizaat_ikhtar ?? 0 }}"
data-nizaat_otla="{{ $d->nizaat_otla ?? 0 }}"
data-nizaat_aqdamiya="{{ $d->nizaat_aqdamiya ?? 0 }}"
data-beneficiaires="{{ json_encode($d->beneficiaires_json??[], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) }}">✏️</button>
                                                <form method="post" action="{{ route('dossiers.destroy', $d) }}"
                                                      onsubmit="return confirm('حذف هذا الملف؟');" style="display:inline;">
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
                                @if($dossiers->onFirstPage())
                                    <span>السابق</span>
                                @else
                                    <a href="{{ $dossiers->previousPageUrl() }}">السابق</a>
                                @endif
                                <span>صفحة {{ $dossiers->currentPage() }} / {{ $dossiers->lastPage() }}</span>
                                @if($dossiers->hasMorePages())
                                    <a href="{{ $dossiers->nextPageUrl() }}">التالي</a>
                                @else
                                    <span>التالي</span>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>

                <p style="text-align:center;color:var(--text3);font-size:0.75rem;padding:0 1rem 0.75rem;">
                    لإضافة ملف يدوياً استخدم زر <strong style="color:var(--brown);">إضافة يدوية</strong> أعلى الجدول.
                </p>

            </div><!-- /panels-grid -->
        </div><!-- /content -->
    </main>
</div>

<!-- ═══ MODAL — تفاصيل المبلغ المؤدى ═══════════════════════ -->
<div class="modal-overlay" id="breakdownModal">
    <div class="modal" style="max-width:400px;">
        <div class="modal-header">
            <div class="modal-title">تفاصيل المبلغ المؤدى</div>
            <button type="button" class="modal-close"
                    onclick="document.getElementById('breakdownModal').classList.remove('open')">✕</button>
        </div>
        <table style="width:100%;font-size:0.83rem;">
            <tbody id="breakdownBody"></tbody>
         </table>
        <div class="modal-actions">
            <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('breakdownModal').classList.remove('open')">إغلاق</button>
        </div>
    </div>
</div>

<!-- ═══ MODAL — حساب ════════════════════════════════════════ -->
<div class="modal-overlay" id="calcModal">
    <div class="modal" style="max-width:550px;">
        <div class="modal-header">
            <div class="modal-title">إعداد الحساب</div>
            <button type="button" class="modal-close"
                    onclick="document.getElementById('calcModal').classList.remove('open')">✕</button>
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
                    </select>
                </div>
                <div class="form-group full">
                    <label>وصف الملف (اختياري)</label>
                    <input type="text" name="type_malaf" id="calc_type_malaf" placeholder="يظهر في عمود الملف">
                </div>
                
                <!-- SECTION: المبلغ الأصلي + التعويضات + المصاريف الطبية -->
                <div class="section-divider"><span>💰 تفاصيل المبلغ الأساسي</span></div>
                
                <div class="form-group">
                    <label>المبلغ الأصلي</label>
                    <input type="number" step="0.01" min="0" name="montant_initial" id="calc_montant_initial" oninput="updateCalcPreview()">
                </div>
                <div class="form-group">
                    <label>التعويضات</label>
                    <input type="number" step="0.01" min="0" name="montant_taawidat" id="calc_montant_taawidat" value="0" oninput="updateCalcPreview()">
                </div>
                <div class="form-group">
                    <label>المصاريف الطبية</label>
                    <input type="number" step="0.01" min="0" name="montant_masarif_tibiya" id="calc_montant_masarif_tibiya" value="0" oninput="updateCalcPreview()">
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
            <p style="font-size:0.72rem;color:var(--text3);margin:0.5rem 0;">
                معاينة: <strong id="calcPreviewTotal" style="color:var(--sky-dark);">—</strong> درهم
            </p>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('calcModal').classList.remove('open')">إلغاء</button>
                <button type="button" class="btn btn-ghost" onclick="previewCalcFromForm()">تفاصيل</button>
                <button type="submit" class="btn btn-primary">حفظ الحساب</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══ MODAL — إضافة يدوية ══════════════════════════════════ -->
<div class="modal-overlay" id="manualModal">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <div class="modal-title">➕ إضافة ملف يدوياً</div>
            <button type="button" class="modal-close"
                    onclick="document.getElementById('manualModal').classList.remove('open')">✕</button>
        </div>
        <form method="post" action="{{ route('manual.store') }}">
            @csrf

            <!-- ══ SECTION 1 : المعلومات الأساسية ══ -->
            <div style="background:var(--brown-soft);border:1px solid var(--brown-light);border-radius:9px;padding:0.85rem 1rem;margin-bottom:0.75rem;">
                <div style="font-size:0.72rem;font-weight:700;color:var(--brown);margin-bottom:0.65rem;display:flex;align-items:center;gap:0.4rem;">
                    <span>🗂️</span> المعلومات الأساسية
                </div>
                <div class="modal-form-grid">
                    <div class="form-group">
                        <label>رقم الملف</label>
                        <input type="text" name="numero_dossier" placeholder="2023/1502/430" >
                    </div>
                    <div class="form-group">
                        <label>تاريخ القرار</label>
                        <input type="date" name="date_jugement">
                    </div>
                    <div class="form-group">
                        <label>شركة المشغِّلة</label>
                        <input type="text" name="numero_jugement" placeholder="OCP ">
                    </div>
                    <div class="form-group">
                        <label>نوع الحالة</label>
                        <select name="type_cas" id="manual_type_cas" onchange="onManualTypeCasChange()">
                            <option value="">—</option>
                            <option value="irad_omri">إيراد عمري</option>
                            <option value="irad_omri_ras_mal">رأس مال</option>
                            <option value="masdar_total_taawidat">رأسمال + تعويضات</option>
                            <option value="gharama_ijbariya">غرامة إجبارية</option>
                            <option value="nizaat_shughl">نزاعات الشغل</option>
                            <option value="wafaya_irad_omri">وفاة — إيراد عمري</option>
                            <option value="wafaya_ras_mal">وفاة — رأس مال</option>
                        </select>
                    </div>
                    <!-- شركة التأمين -->
                    <div class="form-group">
                        <label>شركة التأمين</label>
                        <select name="nom_assurance" id="manual_nom_assurance" onchange="onAssuranceChange()">
                            <option value="">— اختر شركة التأمين —</option>
                            <option value="شركة التامين التعاضدية الفلاحية المغربية">شركة التامين التعاضدية الفلاحية المغربية</option>
                            <option value="شركة التامين التعاضدية المركزية المغربية">شركة التامين التعاضدية المركزية المغربية</option>
                            <option value="المكتب المركزي المغربي">المكتب المركزي المغربي</option>
                            <option value="التأمينات لأرباب النقل">التأمينات لأرباب النقل</option>
                            <option value="شركة التأمين أكسا">شركة التأمين أكسا</option>
                            <option value="شركة التامين الملكية الوطنية للتأمين">شركة التامين الملكية الوطنية للتأمين</option>
                            <option value="شركة التأمين النقل">شركة التأمين النقل</option>
                            <option value="شركة التامين الوفاء">شركة التامين الوفاء</option>
                            <option value="شركة التامين اليانز">شركة التامين اليانز</option>
                            <option value="شركة التأمين سند">شركة التأمين سند</option>
                            <option value="شركة التامين سنلام المغرب">شركة التامين سنلام المغرب</option>
                            <option value="شركة التامين اطلنطا سند">شركة التامين اطلنطا سند</option>
                            <option value="autre">➕ شركة أخرى (إضافة جديدة)</option>
                        </select>
                    </div>
                    <!-- Nouvelle entreprise -->
                    <div class="form-group" id="wrap_autre_nom" style="display:none">
                        <label>اسم الشركة الجديدة</label>
                        <input type="text" id="manual_nom_assurance_autre" placeholder="أدخل اسم الشركة"
                               oninput="previewCustomAssurance()">
                    </div>
                    <!-- Adresse auto-remplie ou saisie -->
                    <div class="form-group full">
                        <label>عنوان الشركة</label>
                        <input type="text" name="adresse_assurance" id="manual_adresse_assurance"
                               placeholder="يُملأ تلقائياً عند اختيار الشركة">
                    </div>
                </div>
            </div>

           <!-- ══ SECTION 2 : تفاصيل المبالغ ══ -->
<div style="background:#FFF8EC;border:1px solid #E8C97A;border-radius:9px;padding:0.85rem 1rem;margin-bottom:0.75rem;">
    <div style="font-size:0.72rem;font-weight:700;color:#92600A;margin-bottom:0.65rem;display:flex;align-items:center;gap:0.4rem;">
        <span>💰</span> تفاصيل المبلغ المؤدى
    </div>
    <div class="modal-form-grid">
        <div class="form-group" id="wrap_manual_montant_initial">
            <label>المبلغ</label>
            <input type="number" step="0.01" min="0" name="montant_initial" id="manual_montant" placeholder="203900 درهم" oninput="updateManualTotal()">
        </div>
        <div class="form-group" id="wrap_manual_taawidat">
            <label>تعويضات يومية</label>
            <input type="number" step="0.01" min="0" name="montant_taawidat" id="manual_montant_taawidat" placeholder="4900 درهم" oninput="updateManualTotal()">
        </div>
        <div class="form-group" id="wrap_manual_masarif">
            <label>المصاريف الطبية</label>
            <input type="number" step="0.01" min="0" name="montant_masarif_tibiya" id="manual_montant_masarif_tibiya" placeholder="13900 درهم" oninput="updateManualTotal()">
        </div>
        <div class="form-group" id="wrap_manual_expertise">
            <label>الخبرة</label>
            <input type="number" name="expertise" placeholder="200 درهم">
        </div>
        <div class="form-group full" id="wrap_manual_janaza" style="display:none">
            <label>مصاريف الجنازة</label>
            <input type="number" step="0.01" min="0" name="masarif_janaza" value="0">
        </div>
        <div class="form-group full" id="wrap_manual_benef" style="display:none">
            <label id="manual_benef_label">مبالغ المستفيدين</label>
            <div id="manual_benef_list"></div>
            <button type="button" class="btn btn-ghost btn-sm" style="margin-top:0.4rem;" onclick="addManualBenefRow()">＋ مستفيد</button>
        </div>

        <!-- نزاعات الشغل fields -->
        <div class="form-group full" id="wrap_manual_nizaat" style="display:none">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.7rem;margin-bottom:0.5rem;">
                <div class="form-group">
                    <label>الضرر</label>
                    <input type="number" step="0.01" min="0" id="nizaat_darar" value="0" oninput="syncNizaat('nizaat_darar',this.value); updateNizaatTotal()">
                </div>
                <div class="form-group">
                    <label>الإخطار</label>
                    <input type="number" step="0.01" min="0" id="nizaat_ikhtar" value="0" oninput="syncNizaat('nizaat_ikhtar',this.value); updateNizaatTotal()">
                </div>
                <div class="form-group">
                    <label>العطلة السنوية</label>
                    <input type="number" step="0.01" min="0" id="nizaat_otla" value="0" oninput="syncNizaat('nizaat_otla',this.value); updateNizaatTotal()">
                </div>
                <div class="form-group">
                    <label>الأقدمية</label>
                    <input type="number" step="0.01" min="0" id="nizaat_aqdamiya" value="0" oninput="syncNizaat('nizaat_aqdamiya',this.value); updateNizaatTotal()">
                </div>
            </div>
            <div style="padding:0.5rem 0.75rem;background:#fff;border:1px solid var(--sky);border-radius:7px;font-size:0.8rem;color:var(--sky-dark);">
                مجموع نزاعات الشغل: <strong id="nizaat_sum_preview">0.00</strong> درهم
            </div>
        </div>

        <div class="form-group full" id="wrap_manual_total_preview" style="display:none">
            <label>💰 المبلغ الإجمالي</label>
            <input type="number" step="0.01" name="montant_calcul_total" id="manual_total_preview"
                   style="background:#fff;border-color:#E8C97A;font-weight:700;font-size:1rem;color:#92600A;"readonly>
            <span style="font-size:0.68rem;color:var(--text3);margin-top:2px;" id="manual_total_formula"></span>
        </div>
    </div>
</div>
            <!-- ══ SECTION 3 : المعلومات الشخصية ══ -->
            <div style="background:var(--success-soft);border:1px solid #C0DD97;border-radius:9px;padding:0.85rem 1rem;margin-bottom:0.75rem;">
                <div style="font-size:0.72rem;font-weight:700;color:var(--success);margin-bottom:0.65rem;display:flex;align-items:center;gap:0.4rem;">
                    <span>👤</span> المعلومات الشخصية
                </div>
                <div class="modal-form-grid">
                    <div class="form-group">
                        <label>اسم المصاب</label>
                        <input type="text" name="nom_victime">
                    </div>
                    <div class="form-group">
                        <label>الساكن (ة) ب</label>
                        <input type="text" name="Adres_victime">
                    </div>
                   
                </div>
            </div>
<input type="hidden" id="h_nizaat_darar"    name="nizaat_darar"    value="0">
<input type="hidden" id="h_nizaat_ikhtar"   name="nizaat_ikhtar"   value="0">
<input type="hidden" id="h_nizaat_otla"     name="nizaat_otla"     value="0">
<input type="hidden" id="h_nizaat_aqdamiya" name="nizaat_aqdamiya" value="0">
            <!-- hidden field for custom company name -->
            <input type="hidden" name="nom_assurance_custom" id="manual_nom_assurance_custom">

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('manualModal').classList.remove('open')">إلغاء</button>
                <button type="submit" class="btn btn-success">✅ تسجيل</button>
            </div>
        </form>
    </div>
</div>
<!-- ═══ MODAL — تعديل الملف ══════════════════════════════════ -->
<div class="modal-overlay" id="editModal">
    <div class="modal" style="max-width:580px;">
        <div class="modal-header">
            <div class="modal-title">✏️ تعديل الملف</div>
            <button class="modal-close" onclick="closeEditModal()">✕</button>
        </div>
        <form id="editForm" method="post" action="">
    @csrf
    @method('PUT')

    <!-- ══ SECTION 1 : المعلومات الأساسية ══ -->
    <div style="background:var(--brown-soft);border:1px solid var(--brown-light);border-radius:9px;padding:0.85rem 1rem;margin-bottom:0.75rem;">
        <div style="font-size:0.72rem;font-weight:700;color:var(--brown);margin-bottom:0.65rem;display:flex;align-items:center;gap:0.4rem;">
            <span>🗂️</span> المعلومات الأساسية
        </div>
        <div class="modal-form-grid">
            <div class="form-group"><label>رقم الملف</label><input type="text" name="numero_dossier" id="edit_numero_dossier"></div>
            <div class="form-group"><label>شركة المشغِّلة</label><input type="text" name="numero_jugement" id="edit_numero_jugement"></div>
            <div class="form-group"><label>تاريخ القرار</label><input type="date" name="date_jugement" id="edit_date_jugement"></div>
            <div class="form-group">
                <label>نوع الحالة</label>
<select name="type_cas" id="edit_type_cas" onchange="onEditTypeCasChange()">
    <option value="">اختر النوع</option>
    <option value="irad_omri">إيراد عمري (×10)</option>
    <option value="irad_omri_ras_mal">إيراد عمري رأسمال</option>
    <option value="masdar_total_taawidat">رأسمال إجمالي + تعويضات</option>
    <option value="gharama_ijbariya">غرامة إجبارية</option>
    <option value="nizaat_shughl">نزاعات الشغل</option>
    <option value="wafaya_irad_omri">وفاة — إيراد عمري</option>
    <option value="wafaya_ras_mal">وفاة — رأسمال</option>
</select>
            </div>
           
        </div>
    </div>

    <!-- ══ SECTION 2 : تفاصيل المبالغ ══ -->
    <div style="background:var(--sky-soft);border:1px solid var(--sky);border-radius:9px;padding:0.85rem 1rem;margin-bottom:0.75rem;">
        <div style="font-size:0.72rem;font-weight:700;color:var(--sky-dark);margin-bottom:0.65rem;display:flex;align-items:center;gap:0.4rem;">
            <span>💰</span> تفاصيل المبلغ المؤدى
        </div>
        <div class="modal-form-grid">
            <div class="form-group" id="edit_wrap_montant"><label>المبلغ</label><input type="number" step="0.01" name="montant_initial" id="edit_montant_initial" oninput="updateEditPreview()"></div>
            <div class="form-group" id="edit_wrap_taawidat"><label>التعويضات</label><input type="number" step="0.01" name="montant_taawidat" id="edit_montant_taawidat" value="0" oninput="updateEditPreview()"></div>
            <div class="form-group" id="edit_wrap_masarif"><label>المصاريف الطبية</label><input type="number" step="0.01" name="montant_masarif_tibiya" id="edit_montant_masarif_tibiya" value="0" oninput="updateEditPreview()"></div>
            <div class="form-group"><label>الخبرة</label><input type="number" step="0.01" name="expertise" id="edit_expertise" oninput="updateEditPreview()"></div>
            <div class="form-group" id="edit_wrap_rasemal" style="display:none"><label>رأسمال إجمالي</label><input type="number" step="0.01" min="0" name="montant_rasemal_ijmali" id="edit_montant_rasemal_ijmali" oninput="updateEditPreview()"></div>
            <div class="form-group" id="edit_wrap_taawidat_youmiya" style="display:none"><label>تعويضات يومية</label><input type="number" step="0.01" min="0" name="montant_taawidat_youmiya" id="edit_montant_taawidat_youmiya" oninput="updateEditPreview()"></div>
            <div class="form-group full" id="edit_wrap_janaza" style="display:none"><label>مصاريف الجنازة</label><input type="number" step="0.01" min="0" name="masarif_janaza" id="edit_masarif_janaza" value="0" oninput="updateEditPreview()"></div>
           <div class="form-group full" id="edit_wrap_nizaat" style="display:none">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.7rem;margin-bottom:0.5rem;">
        <div class="form-group">
            <label>الضرر</label>
            <input type="number" step="0.01" min="0" name="nizaat_darar" id="edit_nizaat_darar" value="0" oninput="updateEditPreview()">
        </div>
        <div class="form-group">
            <label>الإخطار</label>
            <input type="number" step="0.01" min="0" name="nizaat_ikhtar" id="edit_nizaat_ikhtar" value="0" oninput="updateEditPreview()">
        </div>
        <div class="form-group">
            <label>العطلة السنوية</label>
            <input type="number" step="0.01" min="0" name="nizaat_otla" id="edit_nizaat_otla" value="0" oninput="updateEditPreview()">
        </div>
        <div class="form-group">
            <label>الأقدمية</label>
            <input type="number" step="0.01" min="0" name="nizaat_aqdamiya" id="edit_nizaat_aqdamiya" value="0" oninput="updateEditPreview()">
        </div>
    </div>
</div>
<div class="form-group full" id="edit_wrap_benef" style="display:none">
    <label id="edit_benef_label">مبالغ المستفيدين</label>
    <div id="edit_benef_list"></div>
    <button type="button" class="btn btn-ghost btn-sm" style="margin-top:0.4rem;" onclick="addEditBenefRow('')">+ مستفيد</button>
</div>
            <!-- معاينة المبلغ المؤدى -->
            <div class="form-group full" id="edit_wrap_preview" style="display:none">
                <label>📊 معاينة المبلغ المؤدى</label>
                <input type="text" id="edit_preview_total" readonly
                       style="background:#fff;border-color:var(--sky);font-weight:700;font-size:1rem;color:var(--sky-dark);">
                <span style="font-size:0.68rem;color:var(--text3);margin-top:2px;" id="edit_preview_formula"></span>
            </div>
        </div>
    </div>

    <!-- ══ SECTION 3 : المعلومات الشخصية ══ -->
    <div style="background:var(--success-soft);border:1px solid #C0DD97;border-radius:9px;padding:0.85rem 1rem;margin-bottom:0.75rem;">
        <div style="font-size:0.72rem;font-weight:700;color:var(--success);margin-bottom:0.65rem;display:flex;align-items:center;gap:0.4rem;">
            <span>👤</span> المعلومات الشخصية
        </div>
        <div class="modal-form-grid">
            <div class="form-group"><label>اسم المصاب</label><input type="text" name="nom_victime" id="edit_nom_victime"></div>
            <div class="form-group"><label>شركة التأمين</label><input type="text" name="nom_assurance" id="edit_nom_assurance"></div>
            <div class="form-group full"><label>عنوان الشركة</label><input type="text" name="adresse_assurance" id="edit_adresse_assurance"></div>
        </div>
    </div>

    <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closeEditModal()">إلغاء</button>
        <button type="submit" class="btn btn-success">💾 حفظ التعديلات</button>
    </div>
</form>
    </div><!-- /.modal -->
</div><!-- /#editModal .modal-overlay -->

<!-- ═══ SCRIPTS ═══════════════════════════════════════════════ -->
<script src="{{ asset('js/dossier-calc.js') }}"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const BASE = "{{ url('/') }}";

/* ── Table search ── */
function filterDossierTable(q) {
    q = (q || '').toLowerCase().trim();
    document.querySelectorAll('.dossier-row').forEach(function(tr) {
        var hay = (tr.getAttribute('data-search') || '');
        tr.style.display = !q || hay.includes(q) ? '' : 'none';
    });
}

/* ── Format ── */
function fmt(n) { return (Math.round(parseFloat(n) * 100) / 100).toFixed(2); }
function syncNizaat(field, val) {
    document.getElementById('h_' + field).value = val || '0';
}
/* ── Breakdown table ── */
function fillBreakdownTable(b) {
    var tb = document.getElementById('breakdownBody');
    var rows;

    if (b.type_cas === 'nizaat_shughl') {
        rows = [
            ['الضرر',           b.nizaat_darar    || 0],
            ['الإخطار',         b.nizaat_ikhtar   || 0],
            ['العطلة السنوية',  b.nizaat_otla     || 0],
            ['الأقدمية',        b.nizaat_aqdamiya || 0],
            ['المجموع',         b.montant_original],
            ['الرسم القضائي',   b.rasm_qadai],
            ['حقوق المرافعة',   b.rusum_murafaa],
            ['رسم البحث',       b.rasm_bahth],
            ['المبلغ المؤدى',   b.total],
        ];
    } else if (b.type_cas === 'wafaya_irad_omri' || b.type_cas === 'wafaya_ras_mal') {
        rows = [
            ['مجموع المستفيدين', b.montant],
            ['مصاريف الجنازة',   b.masarif_janaza],
            ['المجموع',          b.montant_original],
            ['الرسم القضائي',    b.rasm_qadai],
            ['حقوق المرافعة',    b.rusum_murafaa],
            ['رسم البحث',        b.rasm_bahth],
            ['الخبرة',           b.expertise],
            ['المبلغ المؤدى',    b.total],
        ];
    } else {
        rows = [
            ['المبلغ',           b.montant],
            ['التعويضات',        b.montant_taawidat || 0],
            ['المصاريف الطبية',  b.montant_masarif_tibiya || 0],
            ['المجموع',          b.montant_original],
            ['الرسم القضائي',    b.rasm_qadai],
            ['حقوق المرافعة',    b.rusum_murafaa],
            ['رسم البحث',        b.rasm_bahth],
            ['الخبرة',           b.expertise],
            ['المبلغ المؤدى',    b.total],
        ];
    }
    // ... reste identique


    tb.innerHTML = rows.map(function(r) {
        var isTotal  = r[0] === 'المبلغ المؤدى';
        var isMajmou = r[0] === 'المجموع';
        var bordered = isTotal || isMajmou;
        return '<tr style="' + (bordered ? 'border-top:2px solid var(--brown-light);border-bottom:2px solid var(--brown-light);font-weight:700;' : '') + '">'
            + '<th style="text-align:right;padding:0.38rem 0.5rem;color:var(--text2);">' + r[0] + '</th>'
            + '<td style="text-align:left;padding:0.38rem 0.5rem;color:' + (isTotal ? 'var(--sky-dark)' : 'var(--text)') + ';">' + fmt(r[1]) + '</td>'
            + '</tr>';
    }).join('');
}
function openBreakdownPreview(btn) {
    if (!window.DossierCalc) return;
    var payload = JSON.parse(btn.getAttribute('data-payload'));
    // enrich payload with new fields if needed
    payload.montant_taawidat = payload.montant_taawidat || 0;
    payload.montant_masarif_tibiya = payload.montant_masarif_tibiya || 0;
    fillBreakdownTable(window.DossierCalc.buildBreakdown(payload));
    document.getElementById('breakdownModal').classList.add('open');
}
function openBreakdownServer(id) {
    fetch(BASE + '/dossiers/' + id + '/breakdown', { headers: { 'Accept': 'application/json' } })
        .then(function(r) { return r.json(); })
        .then(function(b) { fillBreakdownTable(b); document.getElementById('breakdownModal').classList.add('open'); });
}

/* ── File select feedback ── */
function handleFileSelect(input) {
    var file  = input.files[0];
    var zone  = document.getElementById('uploadZone');
    var icon  = document.getElementById('uploadIcon');
    var title = document.getElementById('uploadTitle');
    var sub   = document.getElementById('fileName');
    if (file) {
        zone.style.borderColor = 'var(--sky)';
        zone.style.background  = 'var(--sky-soft)';
        icon.textContent       = '✅';
        title.textContent      = file.name;
        title.style.color      = 'var(--sky-dark)';
        var size = file.size < 1024*1024
            ? (file.size/1024).toFixed(1) + ' KB'
            : (file.size/(1024*1024)).toFixed(1) + ' MB';
        sub.textContent = '📎 ' + size + ' — جاهز للتحليل';
        sub.style.color = 'var(--sky-dark)';
    } else {
        zone.style.borderColor = ''; zone.style.background = '';
        icon.textContent = '📤'; title.textContent = 'اسحب الملف هنا أو انقر للاختيار';
        title.style.color = ''; sub.textContent = 'PDF أو JPG أو PNG — بحد أقصى 80 ميغابايت'; sub.style.color = '';
    }
}

/* ── Calc modal helpers ── */
function clearCalcBenefList() { var b = document.getElementById('calc_benef_list'); if(b) b.innerHTML=''; }
function addCalcBenefRow(val) {
    val = val==null ? '' : val;
    var box = document.getElementById('calc_benef_list'); if(!box) return;
    var d = document.createElement('div'); d.style.marginBottom='0.3rem';
    d.innerHTML = '<input type="number" step="0.01" min="0" name="beneficiaires[][montant]" value="'
        + String(val).replace(/"/g,'&quot;') + '" oninput="updateCalcPreview()">';
    box.appendChild(d);
}
function toggleCalcExtra() {
    var t = document.getElementById('calc_type_cas').value;
    document.getElementById('wrap_rasemal').style.display  = t==='masdar_total_taawidat' ? '' : 'none';
    document.getElementById('wrap_taawidat').style.display = t==='masdar_total_taawidat' ? '' : 'none';
    var waf = t==='wafaya_irad_omri'||t==='wafaya_ras_mal';
    document.getElementById('wrap_janaza').style.display = waf ? '' : 'none';
    document.getElementById('wrap_benef').style.display  = waf ? '' : 'none';
    if (!waf) { clearCalcBenefList(); }
    else if (document.getElementById('calc_benef_list').children.length===0) { addCalcBenefRow(''); addCalcBenefRow(''); }
    updateCalcPreview();
}
function readCalcPayloadFromForm() {
    var ben=[];
    document.querySelectorAll('#calc_benef_list input[name^="beneficiaires"]').forEach(function(i){ ben.push({montant:parseFloat(i.value)||0}); });
    return {
        type_cas: document.getElementById('calc_type_cas').value,
        montant_initial: parseFloat(document.getElementById('calc_montant_initial').value)||0,
        montant_taawidat: parseFloat(document.getElementById('calc_montant_taawidat').value)||0,
        montant_masarif_tibiya: parseFloat(document.getElementById('calc_montant_masarif_tibiya').value)||0,
        montant_rasemal_ijmali: parseFloat(document.getElementById('calc_montant_rasemal_ijmali').value)||0,
        montant_taawidat_youmiya: parseFloat(document.getElementById('calc_montant_taawidat_youmiya').value)||0,
        masarif_janaza: parseFloat(document.getElementById('calc_masarif_janaza').value)||0,
        expertise: parseFloat(document.getElementById('calc_expertise').value)||0,
        beneficiaires_json: ben,
    };
}
function updateCalcPreview() {
    if (!window.DossierCalc) return;
    document.getElementById('calcPreviewTotal').textContent = fmt(window.DossierCalc.buildBreakdown(readCalcPayloadFromForm()).total);
}
function openCalcModal(id, el) {
    id = parseInt(id);
    var p = JSON.parse(el.getAttribute('data-payload')||'{}');
    document.getElementById('calcForm').action = BASE+'/calculate/'+id;
    document.getElementById('calc_type_cas').value = p.type_cas||'autre';
    document.getElementById('calc_montant_initial').value = p.montant_initial??'';
    document.getElementById('calc_montant_taawidat').value = p.montant_taawidat??0;
    document.getElementById('calc_montant_masarif_tibiya').value = p.montant_masarif_tibiya??0;
    document.getElementById('calc_expertise').value = p.expertise??'';
    document.getElementById('calc_montant_rasemal_ijmali').value = p.montant_rasemal_ijmali??'';
    document.getElementById('calc_montant_taawidat_youmiya').value = p.montant_taawidat_youmiya??'';
    document.getElementById('calc_masarif_janaza').value = p.masarif_janaza??'';
    document.getElementById('calc_type_malaf').value = p.type_malaf||'';
    clearCalcBenefList();
    (p.beneficiaires_json||[]).forEach(function(r){ addCalcBenefRow(r&&r.montant!=null?r.montant:''); });
    toggleCalcExtra(); updateCalcPreview();
    document.getElementById('calcModal').classList.add('open');
}
function previewCalcFromForm() {
    if (!window.DossierCalc) return;
    fillBreakdownTable(window.DossierCalc.buildBreakdown(readCalcPayloadFromForm()));
    document.getElementById('breakdownModal').classList.add('open');
}
/* ── Manual modal ── */

// Dictionnaire adresses
var ASSURANCE_ADDRESSES = {
    'شركة التامين التعاضدية الفلاحية المغربية':  '16 زنقة ابو عنان الرباط',
    'شركة التامين التعاضدية المركزية المغربية':   '16 زنقة ابو عنان الرباط',
    'المكتب المركزي المغربي':                     '154 شارع انفا الدار البيضاء',
    'التأمينات لأرباب النقل':                     '215 شارع الزرقطوني الدار البيضاء',
    'شركة التأمين أكسا':                          'شارع الحسن الثاني رقم 120-122 الدار البيضاء',
    'شركة التامين الملكية الوطنية للتأمين':       '83 شارع الجيش الدار البيضاء',
    'شركة التأمين النقل':                         '6 لاكولين سيدي معروف الدار البيضاء',
    'شركة التامين الوفاء':                        '01 شارع عبد المومن الدار البيضاء',
    'شركة التامين اليانز':                        '166 شارع الزرقطوني الدار البيضاء',
    'شركة التأمين سند':                           'الرقم 4 زنقة ايسلي الدار البيضاء',
    'شركة التامين سنلام المغرب':                  '216 شارع الزرقطوني الدار البيضاء',
    'شركة التامين اطلنطا سند':                    '216 شارع الزرقطوني الدار البيضاء',
};

// Entreprises personnalisées sauvegardées (localStorage)
var customAssurances = JSON.parse(localStorage.getItem('customAssurances') || '[]');

function buildAssuranceOptions() {
    var sel = document.getElementById('manual_nom_assurance');
    // Supprimer anciennes options custom (avant "autre")
    Array.from(sel.options).forEach(function(opt) {
        if (opt.getAttribute('data-custom')) opt.remove();
    });
    // Insérer options custom avant "autre"
    var autreOpt = sel.querySelector('option[value="autre"]');
    customAssurances.forEach(function(c) {
        var opt = document.createElement('option');
        opt.value = c.nom;
        opt.textContent = c.nom;
        opt.setAttribute('data-custom', '1');
        sel.insertBefore(opt, autreOpt);
    });
}

function onAssuranceChange() {
    var val = document.getElementById('manual_nom_assurance').value;
    var adresseInput = document.getElementById('manual_adresse_assurance');
    var wrapAutreNom = document.getElementById('wrap_autre_nom');

    if (val === 'autre') {
        wrapAutreNom.style.display = '';
        adresseInput.value = '';
        adresseInput.removeAttribute('readonly');
        adresseInput.style.background = '';
        adresseInput.placeholder = 'أدخل عنوان الشركة الجديدة';
    } else {
        wrapAutreNom.style.display = 'none';
        document.getElementById('manual_nom_assurance_autre').value = '';
        document.getElementById('manual_nom_assurance_custom').value = '';
        // Chercher adresse connue (builtin ou custom)
        var addr = ASSURANCE_ADDRESSES[val] || '';
        if (!addr) {
            var found = customAssurances.find(function(c){ return c.nom === val; });
            if (found) addr = found.adresse;
        }
        if (addr) {
            adresseInput.value = addr;
            adresseInput.setAttribute('readonly', true);
            adresseInput.style.background = 'var(--brown-soft)';
        } else {
            adresseInput.value = '';
            adresseInput.removeAttribute('readonly');
            adresseInput.style.background = '';
            adresseInput.placeholder = 'أدخل عنوان الشركة';
        }
    }
}

function previewCustomAssurance() {
    var nom = document.getElementById('manual_nom_assurance_autre').value.trim();
    document.getElementById('manual_nom_assurance_custom').value = nom;
}

function openManualModal() {
    buildAssuranceOptions();
    document.getElementById('manualModal').classList.add('open');
}

// Sauvegarde entreprise custom à la soumission
document.querySelector('#manualModal form').addEventListener('submit', function() {
    var sel = document.getElementById('manual_nom_assurance');
    if (sel.value === 'autre') {
        var nom = document.getElementById('manual_nom_assurance_autre').value.trim();
        var addr = document.getElementById('manual_adresse_assurance').value.trim();
        if (nom && !customAssurances.find(function(c){ return c.nom === nom; })) {
            customAssurances.push({ nom: nom, adresse: addr });
            localStorage.setItem('customAssurances', JSON.stringify(customAssurances));
            // Ajouter dans ASSURANCE_ADDRESSES pour session
            ASSURANCE_ADDRESSES[nom] = addr;
        }
        // Mettre le vrai nom dans le select pour l'envoyer
        var opt = document.createElement('option');
        opt.value = nom; opt.selected = true;
        sel.appendChild(opt);
        sel.value = nom;
    }
});

function onManualTypeCasChange() {
    var type     = document.getElementById('manual_type_cas').value;
    var isWafa   = type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal';
    var isFoisDix= type === 'wafaya_irad_omri';
    var isNizaat = type === 'nizaat_shughl';

    document.getElementById('wrap_manual_montant_initial').style.display = (isWafa || isNizaat) ? 'none' : '';
    document.getElementById('wrap_manual_taawidat').style.display        = (isWafa || isNizaat) ? 'none' : '';
    document.getElementById('wrap_manual_masarif').style.display         = (isWafa || isNizaat) ? 'none' : '';
    document.getElementById('wrap_manual_expertise').style.display       = isNizaat ? 'none' : '';

    // ✅ Vider les valeurs au lieu de disabled
if (isWafa || isNizaat) {
    document.getElementById('manual_montant').value = '';
    document.getElementById('manual_montant_taawidat').value = '';
    document.getElementById('manual_montant_masarif_tibiya').value = '';
}

    document.getElementById('wrap_manual_janaza').style.display = isWafa ? '' : 'none';
    document.getElementById('wrap_manual_benef').style.display  = isWafa ? '' : 'none';
   // Afficher/cacher visuellement
document.getElementById('wrap_manual_nizaat').style.display = isNizaat ? '' : 'none';

// ✅ Changer le type pour forcer l'envoi même si caché
var nizaatFields = ['nizaat_darar','nizaat_ikhtar','nizaat_otla','nizaat_aqdamiya'];
nizaatFields.forEach(function(id) {
    var el = document.getElementById(id);
    if (isNizaat) {
        el.type = 'number';
    } else {
        el.value = '0'; // reset à 0 si pas nizaat
    }
});

    document.getElementById('manual_benef_label').textContent = isFoisDix
        ? 'مبالغ المستفيدين (كل مبلغ × 10)'
        : 'مبالغ المستفيدين';

    if (isWafa && document.getElementById('manual_benef_list').children.length === 0) {
        addManualBenefRow(); addManualBenefRow();
    }
    if (!isWafa) document.getElementById('manual_benef_list').innerHTML = '';

    if (isNizaat) updateNizaatTotal();
    updateManualTotal();
}

function updateNizaatTotal() {
    var darar    = parseFloat(document.getElementById('nizaat_darar').value)    || 0;
    var ikhtar   = parseFloat(document.getElementById('nizaat_ikhtar').value)   || 0;
    var otla     = parseFloat(document.getElementById('nizaat_otla').value)     || 0;
    var aqdamiya = parseFloat(document.getElementById('nizaat_aqdamiya').value) || 0;
    var sum = darar + ikhtar + otla + aqdamiya;

    // ✅ Sync hidden fields
    document.getElementById('h_nizaat_darar').value    = darar;
    document.getElementById('h_nizaat_ikhtar').value   = ikhtar;
    document.getElementById('h_nizaat_otla').value     = otla;
    document.getElementById('h_nizaat_aqdamiya').value = aqdamiya;

    // reste du code existant...
    document.getElementById('nizaat_sum_preview').textContent = sum.toFixed(2);
    var wrapPreview = document.getElementById('wrap_manual_total_preview');
    var inputTotal  = document.getElementById('manual_total_preview');
    var formulaSpan = document.getElementById('manual_total_formula');
    inputTotal.value = sum.toFixed(2);
    var parts = [];
    if (darar)    parts.push('الضرر: '          + darar.toFixed(2));
    if (ikhtar)   parts.push('الإخطار: '        + ikhtar.toFixed(2));
    if (otla)     parts.push('العطلة السنوية: ' + otla.toFixed(2));
    if (aqdamiya) parts.push('الأقدمية: '       + aqdamiya.toFixed(2));
    formulaSpan.textContent = parts.length ? parts.join(' + ') + ' = ' + sum.toFixed(2) + ' درهم' : '';
    wrapPreview.style.display = sum > 0 ? '' : 'none';
}
function addManualBenefRow() {
    var box = document.getElementById('manual_benef_list');
    var idx = box.children.length+1;
    var d = document.createElement('div');
    d.style.cssText = 'display:flex;align-items:center;gap:0.5rem;margin-bottom:0.38rem;';
    d.innerHTML = '<span style="font-size:0.72rem;color:var(--text3);min-width:65px;">مستفيد '+idx+'</span>'
        +'<input type="number" step="0.01" min="0" name="beneficiaires[][montant]" value="0" style="flex:1;" oninput="updateManualTotal()">'
        +'<button type="button" onclick="removeManualBenefRow(this)" style="background:var(--error);color:#fff;border:none;border-radius:5px;padding:0.22rem 0.45rem;cursor:pointer;font-size:0.78rem;">✕</button>';
    box.appendChild(d); updateManualTotal();
}
function removeManualBenefRow(btn) {
    btn.closest('div').remove();
    document.querySelectorAll('#manual_benef_list span').forEach(function(s,i){ s.textContent='مستفيد '+(i+1); });
    updateManualTotal();
}
function updateManualTotal() {
    var type      = document.getElementById('manual_type_cas').value;
    var isWafa    = type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal';
    var isFoisDix = type === 'wafaya_irad_omri';
    var isNizaat  = type === 'nizaat_shughl';

    var wrapPreview = document.getElementById('wrap_manual_total_preview');
    var inputTotal  = document.getElementById('manual_total_preview');
    var formulaSpan = document.getElementById('manual_total_formula');

    // ── Cas WAFAT ──────────────────────────────────────────
    if (isWafa) {
        var sum = 0;
        document.querySelectorAll('#manual_benef_list input[name^="beneficiaires"]')
            .forEach(function(i) { sum += parseFloat(i.value) || 0; });
        var total = isFoisDix ? sum * 10 : sum;
        inputTotal.value = total.toFixed(2);
        formulaSpan.textContent = isFoisDix
            ? 'مجموع المستفيدين (' + sum.toFixed(2) + ') × 10 = ' + total.toFixed(2) + ' درهم'
            : 'مجموع المستفيدين = ' + total.toFixed(2) + ' درهم';
        wrapPreview.style.display = '';
        return;
    }

    // ── Cas NIZAAT ─────────────────────────────────────────
    if (isNizaat) {
        var darar    = parseFloat(document.getElementById('nizaat_darar').value)    || 0;
        var ikhtar   = parseFloat(document.getElementById('nizaat_ikhtar').value)   || 0;
        var otla     = parseFloat(document.getElementById('nizaat_otla').value)     || 0;
        var aqdamiya = parseFloat(document.getElementById('nizaat_aqdamiya').value) || 0;
        var sumNizaat = darar + ikhtar + otla + aqdamiya;
        inputTotal.value = sumNizaat.toFixed(2);
        var parts = [];
        if (darar)    parts.push('الضرر: '          + darar.toFixed(2));
        if (ikhtar)   parts.push('الإخطار: '        + ikhtar.toFixed(2));
        if (otla)     parts.push('العطلة السنوية: ' + otla.toFixed(2));
        if (aqdamiya) parts.push('الأقدمية: '       + aqdamiya.toFixed(2));
        formulaSpan.textContent = parts.length
            ? parts.join(' + ') + ' = ' + sumNizaat.toFixed(2) + ' درهم'
            : '';
        wrapPreview.style.display = sumNizaat > 0 ? '' : 'none';
        return;
    }

    // ── Cas STANDARD ───────────────────────────────────────
    var mi           = parseFloat(document.getElementById('manual_montant').value)              || 0;
    var taawidat     = parseFloat(document.getElementById('manual_montant_taawidat').value)     || 0;
    var masarifTibiya= parseFloat(document.getElementById('manual_montant_masarif_tibiya').value)|| 0;

    if (mi > 0 || taawidat > 0 || masarifTibiya > 0) {
        var total = mi + taawidat + masarifTibiya;
        inputTotal.value = total.toFixed(2);
        var parts = [];
        if (mi)            parts.push('المبلغ الأصلي: '   + mi.toFixed(2));
        if (taawidat)      parts.push('تعويضات: '         + taawidat.toFixed(2));
        if (masarifTibiya) parts.push('مصاريف طبية: '     + masarifTibiya.toFixed(2));
        formulaSpan.textContent = parts.join(' + ') + ' = ' + total.toFixed(2) + ' درهم';
        wrapPreview.style.display = '';
    } else {
        wrapPreview.style.display = 'none';
    }
}

/* ── Edit modal ── */
function clearEditBenefList() { var b=document.getElementById('edit_benef_list'); if(b) b.innerHTML=''; }

function addEditBenefRow(val) {
    val = val == null ? '' : val;
    var box = document.getElementById('edit_benef_list'); if (!box) return;
    var idx = box.children.length + 1;
    var d = document.createElement('div');
    d.style.cssText = 'display:flex;align-items:center;gap:0.5rem;margin-bottom:0.38rem;';
    d.innerHTML = '<span style="font-size:0.72rem;color:var(--text3);min-width:65px;">مستفيد ' + idx + '</span>'
        + '<input type="number" step="0.01" min="0" name="beneficiaires[][montant]" value="' + String(val).replace(/"/g, '&quot;') + '" style="flex:1;" oninput="updateEditPreview()">'
        + '<button type="button" onclick="this.closest(\'div\').remove();updateEditPreview();" style="background:var(--error);color:#fff;border:none;border-radius:5px;padding:0.22rem 0.45rem;cursor:pointer;font-size:0.78rem;">✕</button>';
    box.appendChild(d);
    updateEditPreview();
}
function onEditTypeCasChange() {
    var type     = document.getElementById('edit_type_cas').value;
    var isWafa   = type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal';
    var isMasd   = type === 'masdar_total_taawidat';
    var isNizaat = type === 'nizaat_shughl';

document.getElementById('edit_wrap_montant').style.display          = (isWafa || isNizaat) ? 'none' : '';
document.getElementById('edit_wrap_taawidat').style.display         = (isWafa || isMasd || isNizaat) ? 'none' : '';
document.getElementById('edit_wrap_masarif').style.display          = (isWafa || isMasd || isNizaat) ? 'none' : '';
document.getElementById('edit_expertise').closest('.form-group').style.display = isNizaat ? 'none' : '';
    document.getElementById('edit_wrap_rasemal').style.display          = isMasd ? '' : 'none';
    document.getElementById('edit_wrap_taawidat_youmiya').style.display = isMasd ? '' : 'none';
    document.getElementById('edit_wrap_janaza').style.display           = isWafa ? '' : 'none';
    document.getElementById('edit_wrap_benef').style.display            = isWafa ? '' : 'none';
    document.getElementById('edit_wrap_nizaat').style.display           = isNizaat ? '' : 'none';

    document.getElementById('edit_benef_label').textContent = type === 'wafaya_irad_omri'
        ? 'مبالغ المستفيدين (كل مبلغ × 10)'
        : 'مبالغ المستفيدين';

    if (isWafa && document.getElementById('edit_benef_list').children.length === 0) {
        addEditBenefRow(''); addEditBenefRow('');
    }
    if (!isWafa) clearEditBenefList();
    updateEditPreview();
}

function updateEditPreview() {
    if (!window.DossierCalc) return;
    var type   = document.getElementById('edit_type_cas').value;
    var isWafa = type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal';
    var isMasd = type === 'masdar_total_taawidat';

    var ben = [];
    document.querySelectorAll('#edit_benef_list input[name^="beneficiaires"]').forEach(function(i) {
        ben.push({ montant: parseFloat(i.value) || 0 });
    });

 var payload = {
    type_cas                : type,
    montant_initial         : parseFloat(document.getElementById('edit_montant_initial').value) || 0,
    montant_taawidat        : parseFloat(document.getElementById('edit_montant_taawidat').value) || 0,
    montant_masarif_tibiya  : parseFloat(document.getElementById('edit_montant_masarif_tibiya').value) || 0,
    montant_rasemal_ijmali  : parseFloat(document.getElementById('edit_montant_rasemal_ijmali').value) || 0,
    montant_taawidat_youmiya: parseFloat(document.getElementById('edit_montant_taawidat_youmiya').value) || 0,
    masarif_janaza          : parseFloat(document.getElementById('edit_masarif_janaza').value) || 0,
    expertise               : parseFloat(document.getElementById('edit_expertise').value) || 0,
    beneficiaires_json      : ben,
    nizaat_darar            : parseFloat(document.getElementById('edit_nizaat_darar')?.value) || 0,
    nizaat_ikhtar           : parseFloat(document.getElementById('edit_nizaat_ikhtar')?.value) || 0,
    nizaat_otla             : parseFloat(document.getElementById('edit_nizaat_otla')?.value) || 0,
    nizaat_aqdamiya         : parseFloat(document.getElementById('edit_nizaat_aqdamiya')?.value) || 0,
};

    var b = window.DossierCalc.buildBreakdown(payload);

    var wrapPreview  = document.getElementById('edit_wrap_preview');
    var inputTotal   = document.getElementById('edit_preview_total');
    var formulaSpan  = document.getElementById('edit_preview_formula');

    inputTotal.value = fmt(b.total) + ' درهم — المجموع: ' + fmt(b.montant_original) + ' درهم';

    var parts = [];
    if (b.rasm_qadai)   parts.push('رسم قضائي: ' + fmt(b.rasm_qadai));
    if (b.rusum_murafaa)parts.push('مرافعة: '    + fmt(b.rusum_murafaa));
    if (b.rasm_bahth)   parts.push('بحث: '       + fmt(b.rasm_bahth));
    if (b.expertise)    parts.push('خبرة: '      + fmt(b.expertise));
    formulaSpan.textContent = parts.join(' + ') + ' = ' + fmt(b.total) + ' درهم';

    wrapPreview.style.display = type ? '' : 'none';
}

function openEditModalFromButton(el) {
    document.getElementById('editForm').action = BASE + '/dossiers/' + el.dataset.id;

    ['numero_dossier','numero_jugement','date_jugement','nom_victime','nom_assurance',
 'adresse_assurance','montant_initial','expertise','type_cas','type_malaf',
 'montant_rasemal_ijmali','montant_taawidat_youmiya','masarif_janaza',
 'montant_taawidat','montant_masarif_tibiya',
 'nizaat_darar','nizaat_ikhtar','nizaat_otla','nizaat_aqdamiya'].forEach(function(k) {
        var val   = el.dataset[k] || '';
        var input = document.getElementById('edit_' + k);
        if (input) input.value = val;
    });

    clearEditBenefList();
    var benRaw = el.getAttribute('data-beneficiaires');
    if (benRaw) {
        try {
            var arr = JSON.parse(benRaw);
            if (Array.isArray(arr)) arr.forEach(function(r) {
                addEditBenefRow(r && r.montant != null ? r.montant : '');
            });
        } catch(e) {}
    }

    onEditTypeCasChange();
    document.getElementById('editModal').classList.add('open');
}

function closeEditModal() { document.getElementById('editModal').classList.remove('open'); }
/* ── Close modal on backdrop click ── */
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) { if(e.target===this) this.classList.remove('open'); });
});

/* ── Save dossier ── */
function toggleSaveBtn(id, checked) {
    var btn = document.getElementById('savebtn-'+id);
    if (checked) { btn.classList.add('visible'); }
    else {
        btn.classList.remove('visible');
        fetch(BASE+'/dossiers/'+id+'/save', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body: JSON.stringify({saved:false})
        });
    }
}
function saveDossier(id) {
    fetch(BASE+'/dossiers/'+id+'/save', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({saved:true})
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (data.success) {
            var btn=document.getElementById('savebtn-'+id);
            btn.textContent='✅'; setTimeout(function(){ btn.textContent='💾'; },1500);
        } else if (data.message) { alert(data.message); }
    });
}
</script>
</body>
</html>