<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أمر تنفيذي واستدعاء</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            font-family: 'Times New Roman', Times, serif;
        }

        /* Page aux dimensions exactes 21cm x 29.7cm (A4) */
        .page {
            width: 21cm;
            min-height: 29.7cm;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 1.2cm 1.5cm 1.5cm 1.5cm;
            margin: 0 auto;
            position: relative;
        }

        /* Tout le contenu en Times New Roman */
        .content {
            font-family: 'Times New Roman', Times, serif;
            height: 100%;
        }

        /* Alignement centre pour les titres */
        .text-center {
            text-align: right;
        }

        /* Style pour les lignes d'en-tête (size 14) */
        .header-line {
            font-size: 16px;
            font-weight: normal;
            margin: 2px 0;
        }
        .header-line-bold {
            font-size: 16px;
            font-weight: bold;
            margin: 2px 0;
        }

        /* 4 étoiles */
        .stars {
            text-align: right;
            font-size: 20px ;
            letter-spacing: 6px;
            margin: 8px 0 6px 0;
            font-weight: bold;
            
        }
        .starsSE {
                    text-align: center;
                    font-size: 28px ;
                    letter-spacing: 6px;
                    margin: 8px 0 6px 0;
                    font-weight: bold;
                    
                }
        /* أمر تنفيذي */
        .exec-order {
            text-align: right;
            font-size: 18px;
            font-weight: normal;
            margin-top: 6px;
        }
        .exec-number {
            text-align: center;
            font-size: 14px;
            font-weight: normal;
            margin-bottom: 3px;
        }

        /* استدعـــــاء size 26 gras */
        .summon {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin: 12px 0 8px 0;
            letter-spacing: 2px;
        }

        /* Paragraphes size 18 */
        .para-18 {
            font-size: 24px;
            font-weight: normal;
            text-align: justify;
            margin-bottom: 14px;
            line-height: 1.45;
        }

        /* Paragraphe avertissement size 16 */
        .para-16 {
            font-size: 20px;
            font-weight: normal;
            text-align: justify;
            margin-bottom: 22px;
            line-height: 1.5;
        }

        /* Signature alignée à gauche, size 14 */
        .signature {
            text-align: left;
            margin-top: 15px;
            font-size: 16px;
            font-weight: normal;
            line-height: 1.7;
        }
        .signature p {
            margin: 2px 0;
        }
        .bold-text {
            font-weight: bold;
        }

        /* Ligne de séparation (tirets) */
        .separator {
            margin: 30px 0 20px 0;
            border-top: 1px dashed #333;
            width: 100%;
        }

        /* Partie basse */
        .lower-section {
            margin-top: 8px;
        }
        .lower-header {
            text-align: right;
            margin-bottom: 8px;
        }
        .lower-header div {
            font-size: 16px;
        }
        .lower-header .bold {
            font-weight: bold;
            font-size: 16px;
        }

        .stars-lower {
            text-align: center;
            font-size: 20px;
            letter-spacing: 6px;
            margin: 6px 0 6px 0;
        }
        .exec-lower {
            text-align: center;
            font-size: 14px;
            font-weight: normal;
            margin: 4px 0;
        }

        /* Ligne cote + nom */
        .bailiff-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            font-size: 14px;
            margin: 15px 0 12px 0;
            border-bottom: 1px dotted #aaa;
            padding-bottom: 5px;
        }

        /* attestation size 16, alignée à gauche */
        .attestation {
            font-size: 16px;
            text-align: left;
            margin: 18px 0 12px 0;
            line-height: 1.7;
            font-weight: normal;
        }

        .recipient {
            text-align: left;
            margin-top: 20px;
            font-size: 14px;
            font-weight: normal;
            text-decoration: underline;
        }

        /* Pour que tout tienne sur une seule page */
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white;
            }
            .page {
                box-shadow: none;
                padding: 1.2cm 1.5cm 1.5cm 1.5cm;
                width: 21cm;
                min-height: 29.7cm;
            }
        }

        /* Ajustements pour écran mais garde les proportions A4 */
        @media screen and (max-width: 21cm) {
            .page {
                width: 100%;
                max-width: 21cm;
            }
        }
        
    </style>
</head>
<body>
<div class="page">
    <div class="content">

        <!-- ========== PARTIE HAUTE ========== -->
        <div class="text-center">
            <div class="header-line">المملكة المغربية</div>
            <div class="header-line">وزارة العدل</div>
            <div class="header-line">محكمة الاستئناف بالجديدة</div>
            <div class="header-line-bold">المحكمة الابتدائية بالجديدة</div>
            <div class="header-line-bold" style="margin-top: 5px;">وحدة التحصيل و التبليغ</div>
        </div>

        <div class="stars">****</div>

        <div class="exec-order"><strong>أمــــر تنفيذي:</strong> 189/26</div>

        <div class="summon">استدعـــــاء</div>

        <div class="starsSE">****</div>

        <!-- Corps texte -->
        <div class="para-18">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; نــحـــن رئـــيس كـــتابة الضبـــط بالمحـــكــمة الابتدائية بالجـــديــــدة.
        </div>
        <div class="para-18">
            بناء على القرار الصادر بتاريخ: <b>27/07/2023</b>  &nbsp;&nbsp;الملف عدد:  <b>318/22</b> &nbsp;&nbsp;قضية ح ش.
        </div>
        <div class="para-18">
            نطلب من المسمى : شركة التامين الوفاء في شخص ممثلها القانوني
        </div>
        <div class="para-18">
            الكائن مقرها الاجتماعي ب: 01 شارع عبد المومن الدار البيضاء
        </div>
        <div class="para-18">
            أداء مبلغ الرسوم و المصاريف القضائية المحكوم بها و قدره: <b>781.00</b>  درهما
        </div>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        <div class="para-16">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  و نلفت نظر المحكوم عليه إلى أنه في حالة عدم أداء هذا المبلغ داخل الأجل المحدد سيجبر على التنفيذ بجميع الطرق القانونية مع إضافة 10% من تاريخ استحقاقها و زيادة 5% من الشهر الأول من التأخير و 0.5% عن كل شهر أو جزء شهر إضافي ينصرم بين تاريخ الاستحقاق و تاريخ الأداء (المادة 23 من مدونة التسجيل و التنبر).
        </div>

        <!-- Signature alignée gauche size 14 -->
        <div class="signature">
            <p>حرر بالجديدة بتاريخ: 05-03-2026</p>
            <p>الإمضــاء:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
            <p style="margin-top: 8px;">عن رئيس كتابة الضبط&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
            <p class="bold-text">رشيدة بليلة&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
            </p>
            <p class="bold-text">منتدبة قضائية من الدرجة الاولى</p>
        </div>

        <!-- Ligne de séparation -->
        <div class="separator"></div>

        <!-- ========== PARTIE BASSE ========== -->
        <div class="lower-section">
            <div class="lower-header">
                <div>المملكة المغربية</div>
                <div>وزارة العدل</div>
                <div>محكمة الاستئناف بالجديدة</div>
                <div class="bold">المحكمة الابتدائية بالجديدة</div>
                <div class="bold" style="margin-top: 4px;">وحدة التبليغ والتحصيل</div>
            </div>

            <div class="stars-lower">****</div>

            <div class="exec-lower"><strong>أمر تنفيذي رقم :</strong> 189/26</div>

            <div class="bailiff-row">
                <span>اسم و توقيع عون التبليغ:...................</span>
                <!--<span style="letter-spacing: 6px;">..........</span> -->
            </div>

            <div class="attestation">
                يشهد المسمى(ة): .....................................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
                الموقــــع أسفلـــه أنه توصـــل بالاستدعــاء من أجل أداء الرسوم القضائية المحكوم بها &nbsp;&nbsp;<br>
                 في الملف طرته، و ذلك بتاريخ:.............................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </div>

             <div>
               <p class="recipient">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  توقـــيع الحائـــز</p> 
            </div>
        </div>

    </div>
</div>
</body>
</html>