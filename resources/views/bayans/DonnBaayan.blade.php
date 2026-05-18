<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>كشف الديون — البيان رقم {{ $bayan->group_index }}</title>
  <style>
    body { font-family: Arial, sans-serif; direction: rtl; padding: 20px; background: #fff; font-size: 13px; }
    table { width: 100%; border-collapse: collapse; border: 2px solid #000; }
    th, td { border: 1px solid #000; padding: 4px 8px; text-align: center; white-space: nowrap; }
    thead th { background-color: #f0f0f0; font-weight: bold; }
    .total-row td { font-weight: bold; background-color: #f0f0f0; }
    .col-montant { text-align: left; direction: ltr; }
    .empty-row td { color: #aaa; font-style: italic; }
    @media print {
      body { padding: 5px; }
      .no-print { display: none; }
    }
  </style>
</head>
<body>

<div class="no-print" style="margin-bottom:12px;">
  <button onclick="window.print()" style="padding:6px 16px;font-size:13px;cursor:pointer;">🖨️ طباعة</button>
  <a href="{{ route('wathaiq.index') }}" style="margin-right:10px;font-size:13px;">← العودة</a>
</div>

@php
  $dossiers = $bayan->dossiers->load('calcul');
  $total    = $dossiers->sum(fn($d) => $d->calcul ? (float)$d->calcul->total : 0);
  $count    = $dossiers->count();
@endphp

<table>
  <thead>
    <tr>
      <th rowspan="2">الرقم الترتيبي</th>
      <th rowspan="2">رقم التسجيل</th>
      <th colspan="2">الأمر التنفيذي</th>
      <th rowspan="2">رقم الملف</th>
      <th colspan="2">القرار</th>
      <th rowspan="2">الاسم الكامل للمدين</th>
      <th rowspan="2">رقم البيان</th>
      <th rowspan="2">المبلغ (بالدرهم)</th>
    </tr>
    <tr>
      <th>رقمه</th>
      <th>تاريخه</th>
      <th>رقمه</th>
      <th>تاريخه</th>
    </tr>
  </thead>
  <tbody>
    @forelse($dossiers as $i => $d)
    @php
      $calc      = $d->calcul;
      $montant   = $calc ? number_format((float)$calc->total, 2, ',', ' ') : '—';
      $amrNum    = $calc->numero_amr_tanfidhi  ?? '';
      $amrDate   = $calc->date_generation
                    ? \Carbon\Carbon::parse($calc->date_generation)->format('d/m/Y')
                    : '';
      $qarNum    = $d->numero_jugement ?? '';
      $qarDate   = $d->date_jugement
                    ? \Carbon\Carbon::parse($d->date_jugement)->format('d/m/Y')
                    : '';
    @endphp
    <tr>
      <td>{{ $i + 1 }}</td>
      <td></td>
      <td>{{ $amrNum }}</td>
      <td>{{ $amrDate }}</td>
      <td>{{ $d->numero_dossier ?? '—' }}</td>
      <td></td>
      <td>{{ $qarDate }}</td>
      <td>{{ $d->nom_assurance_normalise ?? $d->nom_assurance ?? '—' }}</td>
      <td>{{ $bayan->group_index }}</td>
      <td class="col-montant">{{ $montant }}</td>
    </tr>
    @empty
    <tr class="empty-row">
      <td colspan="10">لا توجد ملفات مرتبطة بهذا البيان</td>
    </tr>
    @endforelse

    {{-- صفوف فارغة لإكمال 30 --}}
    @if($count < 30)
      @for($j = $count; $j < 30; $j++)
      <tr>
        <td>{{ $j + 1 }}</td>
        <td></td><td></td><td></td><td></td>
        <td></td><td></td><td></td><td></td>
        <td class="col-montant"></td>
      </tr>
      @endfor
    @endif
  </tbody>
  <tfoot>
    <tr class="total-row">
      <td colspan="9" style="text-align:right;">المجموع</td>
      <td class="col-montant">
        <strong>{{ number_format($total, 2, ',', ' ') }}</strong>
      </td>
    </tr>
  </tfoot>
</table>

</body>
</html>