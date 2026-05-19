<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>أمر تنفيذي لاستخلاص صوائر رسوم المساعدة القضائية</title>
    <style>
        *{
   box-sizing:border-box;
    font-weight: bold;
}
       body {
    font-family: 'Traditional Arabic', 'Times New Roman', serif;
    background: white;
    direction: rtl;
    font-weight: bold;
}
        @page{
    size:A4;
    margin:0;
}

html,body{
    margin:0;
    padding:0;
}
.page{
    width:210mm;
    min-height:297mm;
    padding:8mm;
    margin:auto;
    background:white;
}

.outer-table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    border-right:2px solid black;
}

.col-left{
    width:30%;
    vertical-align:top;
    padding:0 6px;
    border-left:2px solid black;
    text-align:center;
}

.col-right{
    width:70%;
    vertical-align:top;
    padding:0 8px;
}
@media print{

    body{
        background:white;
        padding:0;
        margin:0;
    }

    .page{
        width:210mm;
        min-height:297mm;

        margin:0;

        box-shadow:none;

        page-break-after:always;
    }

}

   body{
    font-family:'Traditional Arabic','Times New Roman',serif;
    background:#cfcfcf;
    direction:rtl;
    padding:20px 0;
}

.page{
    width:210mm;
    min-height:297mm;

    background:white;

    margin:20px auto;

    padding:10mm;

    box-shadow:0 0 15px rgba(0,0,0,0.25);

    position:relative;

    overflow:hidden;
}

    *{
        zoom:1 !important;
        transform:none !important;
    }


      .header-block {
    font-size: 18px;
    line-height: 1.3;
    text-align: center;
}
        .header-block .underline { text-decoration: underline; }

        .raqm-table {
            border-collapse: collapse;
            margin: 8px auto;
            font-size: 12px;
            font-weight: bold;
        }
        .raqm-table td {
            border: 2px solid black;
            width: 22px;
            height: 22px;
            text-align: center;
            padding: 1px;
        }
        .raqm-table td.label {
            width: auto;
            padding: 1px 6px;
            white-space: nowrap;
        }

      .left-info {
    font-size: 18px;
    line-height: 1.9;
    text-align: right;
    margin-top: 2px;
}


        .doc-title {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 6px;
            color: red;
        }
      .doc-body {
    font-size: 20px;
    line-height: 1.8;
    text-align: right;
}
        .doc-body p { margin-bottom: 3px; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .italic { font-style: italic; }

        .jaha {
            text-align: left;
            font-weight: bold;
            text-decoration: underline;
        }

        .footer-section {
            margin-top: 8px;
            font-size: 13px;
            line-height: 1;
        }

        @media print {
            body { padding: 0; margin: 0; background: white; }
            .page { box-shadow: none; }
        }
    </style>
</head>
<body>
    
<div class="page">

    <table class="outer-table">
        <tr>
            <!-- العمود الأيسر -->
            <td class="col-left">
                <div class="header-block">
                    <span class="underline">المملـكة المـغـــربيــة</span><br>
                    وزارة العـــــدل<br>
                    محكمة الاستئناف بالجـديـدة<br>
                    <strong>المحكمة الإبتدائية بالجديدة</strong><br>
                    <strong>وحدة التبليغ و التحصيل</strong><br>
                    *****
                </div>

                <!-- الرقم الضابط -->
                
@php
    $numStr = '050000';
@endphp

<table class="raqm-table" style="direction:ltr;">
    <tr>
        <td class="label" style="direction:rtl;">الرقم الضابط</td>
        @foreach(str_split($numStr) as $digit)
            <td>{{ $digit }}</td>
        @endforeach
    </tr>
</table>
                       
                <!-- معلومات يسار -->
                <div class="left-info">
                    <p>الرقم المطابق بسجل صوائر الرسوم القضائية التكميلية............</p>
                    <p>أمر تنفيذي رقم: <strong >{{ $calcul->numero_amr_tanfidhi ?? '—' }}</strong></p>
                    <p>من داخل البيان رقم: <strong>{{ ($dossier->bayan_id ?? 0) + 15 }}</strong></p>
                    <p>ملف رقم : <strong >{{ $dossier->numero_dossier ?? '—' }}</strong></p>
                    <p>حكم أو قرار رقم: </p>
                </div>

                <!-- تفصيل -->
                <div style="font-size:15px; font-weight:bold; text-align:right; margin-top:8px;">تفصـــيل</div>
                <div style="font-size:14px; text-align:right; margin-top:2px; margin-bottom:4px;">
                    حساب المبالغ المأمور باستخلاصها
                </div>

                <!-- جدول المبالغ -->
<table style="border-collapse:collapse; width:100%; font-size:15px; direction:rtl;">
    <tr>
        <td style="padding:2px 6px; text-align:right; vertical-align:middle;">الرسوم القضائية:</td>
        <td style="border:1.5px solid black; width:85px; text-align:center; font-weight:bold; padding:4px 5px;">
            {{ number_format(ceil((float)($calcul->rasm_qadai ?? 0)), 2) }}
        </td>
    </tr>
    <tr>
        <td style="padding:2px 6px; text-align:right; vertical-align:middle;">تسبيقات الخزينة</td>
        <td style="border:1.5px solid black; width:85px; text-align:center; font-weight:bold; padding:4px 5px;">
           @if(($calcul->expertise ?? 0) > 0)
    {{ number_format((float)$calcul->expertise, 2) }}
@endif
        </td>
    </tr>
    <tr>
        <td style="padding:2px 6px; text-align:right; vertical-align:middle; font-size:10px; line-height:1.4;">مساهمة الشغالين العادمي التأمين في<br>حوادث الشغل</td>
        <td style="border:1.5px solid black; width:85px; height:40px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding:2px 6px; text-align:right; vertical-align:middle;">رسم البحث</td>
        <td style="border:1.5px solid black; width:85px; text-align:center; font-weight:bold; padding:4px 5px;">
            {{ number_format((float)($calcul->rasm_bahth ?? 0), 2) }}
        </td>
    </tr>
    <tr>
        <td style="padding:2px 6px; text-align:right; vertical-align:middle;">حقوق المرافعة</td>
        <td style="border:1.5px solid black; width:85px; text-align:center; font-weight:bold; padding:4px 5px;">
            {{ number_format((float)($calcul->rusum_murafaa ?? 0), 2) }}
        </td>
    </tr>
    <tr>
        <td style="padding:2px 6px; text-align:right; vertical-align:middle;"><strong>المجموع(بالدرهم):</strong></td>
        <td style="border:1.5px solid black; width:85px; text-align:center; font-weight:bold; padding:4px 5px; color:blue;">
           @php
    $total = (float)($calcul->rasm_qadai ?? 0)
           + (float)($calcul->expertise ?? 0)
           + (float)($calcul->rasm_bahth ?? 0)
           + (float)($calcul->rusum_murafaa ?? 0);
    $total = ceil($total);
@endphp
{{ number_format($total, 2) }}
        </td>
    </tr>
</table>

            <!-- العمود الأيمن -->
            <td class="col-right">
                <div class="doc-title">
                    أمر تنفيذي لاستخلاص صوائر رسوم المساعدة القضائية
                </div>

                <div class="doc-body">
                    <p>مختصر مستخرج من الأصل المحفوظ بكتابة الضبط بالمحكمة الابتدائية بالجديدة .</p>

                    <p>قرار صادر بتاريخ : &nbsp;&nbsp;
                        <strong >
                            {{ $dossier->date_jugement ? \Carbon\Carbon::parse($dossier->date_jugement)->format('d/m/Y') : '—' }}
                        </strong>
                    </p>

                    <p>بين المسمى : <strong>الحساب الخاص لوزارة العدل</strong></p>

                    <p class="jaha">من جهة</p>

                    <p>و المسمى : <strong >{{ $dossier->nom_assurance ?? '—' }}</strong> في شخص ممثلها القانوني و أعضاء مجلسها الإداري</p>
                    <p></p>
                    <p>الكائن مقرها الإجتماعي ب: {{ $dossier->adresse_assurance ?? '—' }}</p>
                    <p></p>

                    <p class="jaha">من جهة اخرى</p>

                    <p></p>
                    <p>الذي نص على ما يلي : حكمت المحكمة او اصدرت قرارا يقضي على المسمى :</p>
                    <p></p>
                    <p><strong>{{ $dossier->nom_assurance ?? '—' }}</strong> في شخص ممثلها القانوني</p>
                    <p></p>
                    <p class="italic">
                        بصوائر الدعوى التي تبلغ (بالحروف) :
                       <strong>{{ $calcul->total_en_lettres_ar ?? '—' }}</strong>
                    </p>
                </div>
            </td>
        </tr>
    </table>

    <!-- الذيل -->
    <div class="footer-section">
        <p style="text-align:left; font-weight:bold; text-decoration:underline; font-size:14px; margin-top:6px;">
            مختصر مطابق للأصل سلم من أجل التنفيذ
        </p>
        <p style="text-align:left; font-size:13px;">
            حرر بالجديدة <span style="text-decoration:underline;"><strong>بتاريخ :</strong></span>
            <strong >{{ now()->format('d-m-Y') }}</strong>
        </p>
        <p style="text-align:left; font-size:13px; margin-top:2px;">عن رئيس كتابة الضبط</p>
        <p style="text-align:left; font-size:13px; font-style:italic; margin-top:2px;">رشيدة بليلة &nbsp;&nbsp; &nbsp;&nbsp;</p>
        <p style="text-align:left; font-size:12px;">منتدبة قضائية من الدرجة‌الاولى</p>
        <p style="text-align:left; font-weight:bold; font-size:14px; text-decoration:underline; margin-top:6px;">نمـــوذج 80020</p>
    </div>

</div>
</body>
</html>