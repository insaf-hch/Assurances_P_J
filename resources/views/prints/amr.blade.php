<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Ordre exécutoire - Tribunal de première instance - Impression</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #e2e6e9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Times New Roman', Times, serif, 'Courier New', monospace;
            padding: 30px 20px;
        }

        /* Feille imprimable (format A4 / proche A4) */
        .document {
            max-width: 1100px;
            width: 100%;
            background: white;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
            padding: 1.8cm 1.5cm 2cm 1.5cm;
            transition: all 0.2s;
            border-radius: 2px;
        }

        /* Styles typographiques pour respecter le cachet officiel */
        .header-maroc {
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            line-height: 1.4;
            margin-bottom: 0.75rem;
        }

        .header-maroc h1 {
            font-size: 1.3rem;
            font-weight: 800;
            margin: 0;
        }

        .ministere {
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 2px;
        }

        .tribunal {
            font-size: 1rem;
            font-weight: 700;
            margin-top: 5px;
        }

        .sub-line {
            font-size: 0.9rem;
            font-weight: 500;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding-bottom: 2px;
            margin-top: 3px;
        }

        .etoiles {
            font-family: monospace;
            letter-spacing: 2px;
            font-size: 1rem;
            margin-top: 6px;
            margin-bottom: 10px;
        }

        /* lignes d'informations */
        .reg-numbers {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 10px 0 8px 0;
            margin: 12px 0 8px 0;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .numero-controle {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .ref-line {
            margin: 6px 0 4px 0;
            font-size: 0.95rem;
        }

        .ref-line span {
            font-weight: bold;
        }

        .double-ligne {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 5px 0;
        }

        .exec-number {
            font-weight: bold;
            font-size: 1rem;
        }

        /* tableau des sommes */
        .sommes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0 18px 0;
            font-size: 0.95rem;
            font-family: 'Courier New', monospace;
        }

        .sommes-table td, .sommes-table th {
            border: 1px solid #000;
            padding: 8px 10px;
            vertical-align: top;
        }

        .sommes-table th {
            background-color: #f1f1f1;
            font-weight: 700;
            text-align: center;
        }

        .montant-cell {
            text-align: right;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f2e0;
        }

        /* titre ordre exécutoire */
        .ordre-titre {
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            text-decoration: underline;
            margin: 15px 0 8px 0;
        }

        .soustitle {
            text-align: center;
            font-style: italic;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        /* partie décision / extrait */
        .extrait-texte {
            margin: 20px 0 15px 0;
            line-height: 1.45;
            text-align: justify;
            font-size: 0.95rem;
        }

        .extrait-texte p {
            margin: 8px 0;
        }

        .bold {
            font-weight: 700;
        }

        .mention-montant {
            font-weight: bold;
            font-size: 1rem;
            background: #fef5e0;
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
        }

        .date-signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            border-top: 1px dashed #333;
            padding-top: 20px;
        }

        .date-lieu {
            font-size: 0.9rem;
        }

        .signature-block {
            text-align: right;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .cachet {
            font-family: monospace;
            font-weight: bold;
            color: #2c3e2f;
            margin-top: 12px;
            font-size: 0.85rem;
        }

        .modele-note {
            font-size: 0.7rem;
            text-align: right;
            margin-top: 25px;
            color: #3c3c3c;
            border-top: 1px dotted #aaa;
            padding-top: 8px;
        }

        /* pour l'impression: fond blanc, marges propres */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .document {
                box-shadow: none;
                padding: 1.2cm 1.2cm 1.2cm 1.2cm;
                max-width: 100%;
                margin: 0;
                border-radius: 0;
            }
            .signature-block, .date-lieu {
                break-inside: avoid;
            }
            .sommes-table th {
                background-color: #f1f1f1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .total-row {
                background-color: #f9f2e0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        hr {
            margin: 10px 0;
        }

        .small-italic {
            font-size: 0.8rem;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="document">
    <!-- EN-TÊTE OFFICIEL ROYAUME DU MAROC -->
    <div class="header-maroc">
        <div>المملـكة المـغـــربيــة</div>
        <div class="ministere">وزارة العـــــدل</div>
        <div class="tribunal">محكمة الاستئناف بالجـديـدة</div>
        <div class="tribunal">المحكمة الإبتدائية بالجديدة</div>
        <div>وحدة التبليغ و التحصيل</div>
        <div class="etoiles">*****</div>
    </div>

    <!-- Bloc numéro contrôle + registre -->
    <div class="reg-numbers">
        <div>الرقم الضابط &nbsp; <span class="numero-controle">0 0 0 0 5 0</span></div>
        <div class="small-italic">الرقم المطابق بسجل صوائر الرسوم القضائية التكميلية .............................................</div>
    </div>

    <!-- Ligne ordre exécutoire + ref dossier -->
    <div class="double-ligne">
        <div class="exec-number">أمر تنفيذي رقم: 227/26</div>
        <div>من داخل البيان رقم: 08</div>
    </div>
    <div class="double-ligne">
        <div>ملف رقم : 342/23  ح ش</div>
        <div>حكم أو قرار رقم: &nbsp; __________</div>
    </div>

    <!-- Titre du tableau détail -->
    <div style="margin-top: 18px;"><strong>تفصــــيل</strong></div>
    <div style="font-size: 0.8rem; margin-bottom: 5px;">حساب المبالغ المأمور باستخلاصها</div>

    <!-- Tableau des sommes : conforme aux montants indiqués (200, 10, 210) -->
    <table class="sommes-table">
        <thead>
            <tr><th>بيان</th><th>المبلغ (درهم)</th></tr>
        </thead>
        <tbody>
            <tr><td>صوائر المساعدة القضائية / رسوم أساسية</td><td class="montant-cell">200.00</td></tr>
            <tr><td>مصاريف إضافية / أداء</td><td class="montant-cell">10.00</td></tr>
            <tr class="total-row"><td style="font-weight: bold;">المجموع الكلي (بالحروف : مائتان وعشر دراهم)</td><td class="montant-cell">210.00</td></tr>
        </tbody>
    </table>

    <!-- Intitulé ordre exécutoire spécial -->
    <div class="ordre-titre">أمر تنفيذي لاستخلاص صوائر رسوم المساعدة القضائية</div>
    <div class="soustitle">مختصر مستخرج من الأصل المحفوظ بكتابة الضبط بالمحكمة الابتدائية بالجديدة</div>

    <!-- Extrait de la décision / texte du jugement -->
    <div class="extrait-texte">
        <p><span class="bold">قرار صادر بتاريخ :</span> 21/12/2023</p>
        <p><span class="bold">بين المسمى :</span> الحساب الخاص لوزارة العدل &nbsp;&nbsp;&nbsp; <span class="bold">من جهة</span><br>
        <span class="bold">و المسمى :</span> شركة التأمين الوفاء في شخص ممثلها القانوني و أعضاء مجلسها الإداري</p>
        <p>الكائن مقرها الإجتماعي ب: 01 شارع عبد المومن – الدار البيضاء &nbsp;&nbsp;&nbsp; <span class="bold">من جهة اخرى</span></p>
        <p><span class="bold">الذي نص على ما يلي :</span> حكمت المحكمة او اصدرت قرارا يقضي على المسمى :<br>
        <span class="bold">شركة التأمين الوفاء في شخص ممثلها القانوني</span><br>
        بصوائر الدعوى التي تبلغ (بالحروف) : <span class="mention-montant">مائتان وعشر دراهم</span>.</p>
        <p><span class="bold">مختصر مطابق للأصل سلم من أجل التنفيذ</span></p>
    </div>

    <!-- Date et signature (conformes au texte original) -->
    <div class="date-signature">
        <div class="date-lieu">
            حرر بالجديدة بتاريخ : ‏‏‏‏‏‏‏‏17‏-03‏-2026
        </div>
        <div class="signature-block">
            عن رئيس كتابة الضبط<br>
            <span style="font-weight: bold; font-size: 1.05rem;">رشيدة بليلة</span><br>
            منتدبة قضائية من الدرجة الأولى<br>
            <div class="cachet">[مختوم]</div>
        </div>
    </div>

    <!-- Modèle / référence formulaire -->
    <div class="modele-note">
        نمـــوذج 80020
    </div>

    <!-- petite mention d'authenticité pour l'impression (purement informative) -->
    <div style="font-size: 0.7rem; text-align: center; margin-top: 18px; color: #5f5f5f;">
        مستخرج للتنفيذ - نسخة قابلة للطباعة - الأصل محفوظ بكتابة الضبط
    </div>
</div>
</body>
</html>