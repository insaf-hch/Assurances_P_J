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
    --bg:         #F7F4F0;
    --surface:    #FFFFFF;
    --surface2:   #F0EAE3;
    --border:     #E4D9CE;
    --border2:    #CDBFB0;
    --text:       #2B1A0E;
    --text2:      #6B5040;
    --text3:      #A8917C;

    --brown:      #7B4F2C;
    --brown2:     #9C6B4A;
    --brown-soft: #F5ECE3;
    --brown-light:#E8D5C2;

    --sky:        #0EA5C9;
    --sky2:       #38BDF8;
    --sky-soft:   #E0F6FD;
    --sky-dark:   #0369A1;

    --success:      #2D7A3A;
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
    padding: 0.65rem 0.75rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle; text-align: right;
}
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--sky-soft); }

/* ─── PROGRESS BAR ────────────────────────────────────────── */
.progress-wrap {
    display: inline-flex; align-items: center; gap: 0.5rem;
}
.progress-bar-bg {
    display: inline-block; width: 90px; height: 6px;
    background: var(--border);
    border-radius: 3px; overflow: hidden;
}
.progress-bar-fill {
    display: block; height: 100%;
    background: linear-gradient(90deg, var(--sky) 0%, var(--sky2) 100%);
    border-radius: 3px;
    transition: width 0.3s ease;
}
.progress-count {
    font-size: 0.75rem; font-weight: 600; color: var(--text2);
    min-width: 40px;
}

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
.btn-secondary {
    background: var(--brown-soft);
    color: var(--brown);
    border: 1px solid var(--brown-light);
}
.btn-secondary:hover { background: var(--brown-light); }
.btn-sm { padding: 0.28rem 0.65rem; font-size: 0.72rem; }

/* ─── ALERT ───────────────────────────────────────────────── */
.alert-success {
    background: var(--success-soft); color: var(--success);
    border: 1px solid #C0DD97;
    padding: 0.72rem 1rem; border-radius: 9px;
    margin-bottom: 0.875rem; font-size: 0.83rem;
    display: flex; align-items: center; gap: 0.5rem;
}

/* ─── EMPTY ───────────────────────────────────────────────── */
.empty { text-align: center; padding: 4rem 1.5rem; color: var(--text3); }
.empty-icon  { font-size: 2.8rem; margin-bottom: 0.75rem; opacity: 0.4; }
.empty-title { font-size: 0.95rem; font-weight: 600; color: var(--text2); margin-bottom: 0.35rem; }
.empty-sub   { font-size: 0.8rem; }

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
            <a href="{{ route('home') }}" class="nav-item">
                <span class="nav-icon">🗂️</span> الملفات
            </a>
            <a href="{{ route('wathaiq.index') }}" class="nav-item active">
                <span class="nav-icon">📄</span> الوثائق المُنتجة
            </a>
        </nav>
        <div class="sidebar-footer">نظام معالجة حوادث الشغل — v1.0</div>
    </aside>

    <!-- ═══ MAIN ══════════════════════════════════════════════ -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="topbar-title">الوثائق المُنتجة</div>
                <div class="topbar-sub">قائمة البيانات المُولَّدة — البيانات السنوية</div>
            </div>
            <div class="topbar-actions">
                <span class="badge">📅 {{ now()->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="content">

            @if(session('success'))
                <div class="alert-success">✅ {{ session('success') }}</div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <div class="panel-title-dot"></div>
                        البيانات المُنتجة
                    </div>
                    @isset($bayans)
                        <span class="badge">{{ $bayans->count() }} بيان</span>
                    @endisset
                </div>

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
                                    @php
                                        $percent = min(100, ($b->dossiers_count / 30) * 100);
                                    @endphp
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
                                                <a class="btn btn-primary btn-sm"
                                                   href="{{ route('bayans.show', $b) }}">
                                                    عرض البيان
                                                </a>
                                                <a class="btn btn-secondary btn-sm"
                                                   href="{{ route('bayans.donn', $b) }}">
                                                    📄 قائمة بيانات
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    <div class="empty">
                        <div class="empty-icon">⚙️</div>
                        <div class="empty-title">البيانات غير متاحة</div>
                        <div class="empty-sub">تأكد من تمرير متغير <code>$bayans</code> من الـ controller.</div>
                    </div>
                @endisset

            </div><!-- /panel -->
        </div><!-- /content -->
    </main>
</div>
</body>
</html>