<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الوثائق المُنتجة — نظام التأمينات</title>
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
    --success: #22c55e;
    --warning: #f59e0b;
    --error: #ef4444;
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
        .panel-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .panel-title-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }

        /* TABLE */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        thead tr { background: var(--surface2); border-bottom: 1px solid var(--border); }
        th { padding: 0.65rem 0.75rem; font-size: 0.7rem; font-weight: 700; color: var(--text3); letter-spacing: 0.04em; text-align: right; white-space: nowrap; }
        td { padding: 0.65rem 0.75rem; border-bottom: 1px solid var(--border); vertical-align: middle; text-align: right; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(123,79,44,0.05); }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border: none; border-radius: 8px; font-family: 'Cairo', sans-serif; font-size: 0.83rem; font-weight: 600; cursor: pointer; transition: all 0.15s; text-decoration: none; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent2); }
        .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.72rem; }

        /* EMPTY */
        .empty { text-align: center; padding: 4rem 1.5rem; color: var(--text3); }
        .empty-icon { font-size: 3rem; margin-bottom: 0.75rem; opacity: 0.4; }
        .empty-title { font-size: 1rem; font-weight: 600; color: var(--text2); margin-bottom: 0.5rem; }
        .empty-sub { font-size: 0.82rem; }

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
            {{-- الصفحة الحالية: الوثائق --}}
            <a href="{{ route('wathaiq.index') }}" class="nav-item active">
                <span class="nav-icon">📄</span> الوثائق المُنتجة
            </a>
        </nav>
        <div class="sidebar-footer">نظام معالجة حوادث الشغل — v1.0</div>
    </aside>

    <!-- MAIN -->
    <main class="main">
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
                <div style="background:rgba(16,185,129,0.1);color:#34d399;border:1px solid rgba(16,185,129,0.25);padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.85rem;">
                    ✅ {{ session('success') }}
                </div>
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
                                    @foreach($bayans as $i => $b)
                                    @php
                                        $percent = min(100, ($b->dossiers_count / 30) * 100);
                                    @endphp
                                    <tr>
                                            
                                            <td><strong>{{ $b->group_index }}</strong></td>
                                            <td>{{ $b->range_label }}</td>
                                            <td>
                                                <span style="display:inline-flex;align-items:center;gap:0.4rem;">
                                                    {{ $b->dossiers_count }} / 30

                                                    <span style="display:inline-block;width:80px;height:6px;background:var(--surface2);border-radius:3px;overflow:hidden;">
                                                        <span style="display:block;height:100%;width:{{$percent}}%;background:var(--accent);border-radius:3px;"></span>
                                                    </span>
                                                </span>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="{{ route('bayans.show', $b) }}">عرض البيان</a>
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
            </div>

        </div><!-- /content -->
    </main>
</div>
</body>
</html>