<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استدعاء </title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            font-family: 'Times New Roman', Times, serif;
        }
        .page {
            width: 21cm;
            min-height: 29.7cm;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 1.2cm 1.5cm 1.5cm 1.5cm;
            margin: 0 auto;
            position: relative;
        }
        .content { font-family: 'Times New Roman', Times, serif; height: 100%; }

        /* En-tête : logo centré + texte à droite sur le même niveau */
        .header-top {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: center;
            position: relative;
            margin-bottom: 4px;
        }
        .header-logo {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
        }
        .header-logo img {
            width: 90px;
            height: auto;
            display: block;
        }
        .header-text {
            text-align: right;
            margin-left: auto;
        }
        .header-line { font-size: 14px; font-weight: normal; margin: 2px 0; }
        .header-line-bold { font-size: 14px; font-weight: bold; margin: 2px 0; }

        .stars {
            text-align: right;
            font-size: 14px;
            letter-spacing: 6px;
            margin: 5px 0 3px 0;
            font-weight: bold;
            line-height: 1.2;
        }
        .starsSE {
            text-align: center;
            font-size: 22px;
            letter-spacing: 6px;
            margin: 5px 0 8px 0;
            font-weight: bold;
            line-height: 1.2;
        }
        .exec-order { text-align: right; font-size: 16px; font-weight: normal; margin-top: 6px; }
        .summon { text-align: center; font-size: 26px; font-weight: bold; margin: 10px 0 6px 0; letter-spacing: 2px; }
        .para-18 { font-size: 22px; font-weight: normal; text-align: justify; margin-bottom: 14px; line-height: 1.45; }
        .para-16 { font-size: 20px; font-weight: normal; text-align: justify; margin-bottom: 20px; line-height: 1.5; }
        .signature { text-align: left; margin-top: 1px; font-size: 16px; font-weight: normal; line-height: 1; }
        .signature p { margin: 2px 0; }
        .bold-text { font-weight: bold; }
        .separator { margin: 20px 0 18px 0; border-top: 1px dashed #333; width: 100%; }
        .lower-section { margin-top: 5px; position: relative; min-height: 280px; font-family: 'Times New Roman', Times, serif; }
        .delivery-box { width: 180px; margin: 0 auto 20px auto; border: 1px solid #333; text-align: center; padding: 6px 10px; font-size: 28px; font-weight: bold; background: white; }
        .right-info { position: absolute; right: 0; top: 50px; width: 260px; text-align: right; font-size: 18px; }
        .stars-mini { text-align: right; font-size: 18px; letter-spacing: 4px; margin: 5px 0 3px 0; font-weight: bold; }
        .exec-num { margin: 8px 0 5px 0; font-size: 18px; font-weight: normal; }
        .agent-sign { text-decoration: underline; margin-top: 8px; font-size: 18px; font-weight: normal; }
        .center-text { margin-right: 200px; text-align: center; font-size: 18px; line-height: 1.6; }
        .center-text p { margin: 12px 0; }
        .line { display: inline-block; width: 280px; border-bottom: 2px dotted #000000; vertical-align: middle; margin: 0 8px; }
        .line.short { width: 200px; }
        .receiver-sign { text-align: left; margin-top: 35px; font-size: 26px; font-weight: bold; text-decoration: underline; padding-left: 60px; }

        @media print {
            body { padding: 0; margin: 0; background: white; }
            .page { box-shadow: none; padding: 1.2cm 1.5cm 1.5cm 1.5cm; width: 21cm; min-height: 29.7cm; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="content">
        <div classe="content"> </div>

        <!-- EN-TÊTE : logo centré + texte à droite, même niveau vertical -->
        <div class="header-top">
            <!-- Logo centré horizontalement -->
            <div class="header-logo">
                <img src="data:image/png;base64,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" alt="شعار المملكة المغربية">
            </div>
            <!-- Texte aligné à droite, au même niveau que le logo -->
            <div class="header-text">
                <div class="header-line">المملكة المغربية</div>
                <div class="header-line">وزارة العدل</div>
                <div class="header-line">محكمة الاستئناف بالجديدة</div>
                <div class="header-line-bold">المحكمة الابتدائية بالجديدة</div>
                <div class="header-line-bold">وحدة التحصيل و التبليغ</div>
            </div>
        </div>

        <div class="stars">****</div>
        <div class="exec-order"><strong>أمــــر تنفيذي:</strong>  <b >{{ $calcul->numero_amr_tanfidhi }}</b></div>
        <div class="summon">استدعـــــاء</div>
        <div class="starsSE">****</div>

        <div class="para-18">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; نــحـــن رئـــيس كـــتابةالضبـــط بالمحـــكــمةالابتدائية بالجـــديــــدة.
        </div>
        <div class="para-18">
            بناء على القرار الصادر بتاريخ: <b >{{ \Carbon\Carbon::parse($dossier->date_jugement)->format('d/m/Y') }}</b> &nbsp;&nbsp;الملف عدد:<b >{{ $dossier->numero_dossier }}</b> &nbsp;&nbsp;قضيةح ش.
        </div>
        <div class="para-18">
            نطلب من المسمى: {{ $dossier->nom_assurance }} في شخص ممثلهاالقانوني
        </div>
        <div class="para-18">
            الكائن مقره الاجتماعي ب: {{ $dossier->adresse_assurance }}
        </div>
        <div class="para-18">
     أداء مبلغ الرسوم و المصاريف القضائية المحكوم بها و قدره: <b >{{ number_format($calcul->total,2) }}</b> درهما
        </div>

        <div class="para-16">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; و نلفت نظر المحكوم عليه إلى أنه في حالة عدم أداء هذا المبلغ داخل الأجل المحدد سيجبر على التنفيذ بجميع الطرق القانونية مع إضافة 10% من تاريخ استحقاقها و زيادة 5% من الشهر الأول من التأخير و 0.5% عن كل شهر أو جزء شهر إضافي ينصرم بين تاريخ الاستحقاق و تاريخ الأداء (المادة 23 من مدونة التسجيل و التنبر).
        </div>

        <div class="signature">
            <p>حرر بالجديدة بتاريخ: <b >{{ \Carbon\Carbon::parse($dossier->date_export)->format('d-m-Y') }}</b> </p>
            <p>الإمضــاء:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            <br>
            <p style="margin-top: 8px;">عن رئيس كتابة الضبط&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            <p class="bold-text">رشيدة بليلة&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            <p class="bold-text">منتدبة قضائية من الدرجة الاولى</p>
        </div>

        <div class="separator"></div>

       <div class="lower-section">
    <div class="delivery-box">شهادة تسليم</div>

    <!-- نقل المعلومات لأعلى مباشرة بعد السطر الفاصل -->
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 15px;">
        
        <!-- النص الأيسر -->
        <div class="center-text" style="margin-right:0; flex:1;">
            <p>يشهد المسمى(ة) : <span class="line"></span></p>
            <p>الموقع أسفله أنه توصل بالاستدعاء من أجل أداء الرسوم القضائية المحكوم</p>
            <p>بها في الملف طره، و ذلك بتاريخ <span class="line short"></span></p>
            <div class="receiver-sign">توقيع الحائز</div>
        </div>

        <!-- المعلومات اليمنى — مباشرة بعد الفاصل -->
        <div class="right-info" style="position:static; width:240px; font-size:15px; line-height:1.6;">
            <div>المملكة المغربية</div>
            <div>وزارة العدل و الحريات</div>
            <div>محكمة الاستئناف بالجديدة</div>
            <div>المحكمة الابتدائية بالجديدة</div>
            <div><strong>وحدة التبليغ و التحصيل</strong></div>
            <div class="stars-mini">&nbsp;&nbsp;&nbsp;&nbsp;****</div>
            <div class="exec-num">أمر تنفيذي رقم : <b>{{ $calcul->numero_amr_tanfidhi }}</b></div>
            <div class="agent-sign">اسم وتوقيع عون التبليغ</div>
            <p>........................</p>
        </div>

    </div>
</div>

    </div>
</div>
</body>
</html>