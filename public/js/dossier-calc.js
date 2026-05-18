(function (global) {
    'use strict';

    function round2(n) {
        return Math.round((Number(n) || 0) * 100) / 100;
    }

    function sommeBeneficiaires(list, foisDix) {
        if (!Array.isArray(list) || list.length === 0) return 0;
        var sum = 0;
        for (var i = 0; i < list.length; i++) {
            var row = list[i];
            if (!row || typeof row !== 'object') continue;
            var m = parseFloat(row.montant) || 0;
            sum += foisDix ? m * 10 : m;
        }
        return round2(sum);
    }

    function montantPourRasm(state) {
        var type = state.type_cas || 'autre';
        var mi  = parseFloat(state.montant_initial) || 0;
        var mri = parseFloat(state.montant_rasemal_ijmali) || 0;
        var mty = parseFloat(state.montant_taawidat_youmiya) || 0;
        var ben = state.beneficiaires_json;

        switch (type) {
            case 'irad_omri':
                return round2(mi * 10);

            case 'irad_omri_ras_mal':
                return round2(mi);

            case 'masdar_total_taawidat': {
    var mi2       = parseFloat(state.montant_initial)        || 0; // المبلغ = 203900
    var mty2      = parseFloat(state.montant_taawidat_youmiya) || 0; // تعويضات يومية = 4900
    var tibiya2   = parseFloat(state.montant_masarif_tibiya) || 0; // المصاريف الطبية = 13900

    // Base = المبلغ + تعويضات يومية + المصاريف الطبية
    return round2(mi2 + mty2 + tibiya2);
}

            case 'gharama_ijbariya':
                return round2(mi);

            case 'wafaya_irad_omri': {
                // BUG FIX: الرسم القضائي يحسب على المجموع (مستفيدون + جنازة)
                var benSum = sommeBeneficiaires(ben, true);
                var janaza = parseFloat(state.masarif_janaza) || 0;
                return round2(benSum + janaza);
            }

            case 'wafaya_ras_mal': {
                // BUG FIX: الرسم القضائي يحسب على المجموع (مستفيدون + جنازة)
                var benSum = sommeBeneficiaires(ben, false);
                var janaza = parseFloat(state.masarif_janaza) || 0;
                return round2(benSum + janaza);
            }

            case 'nizaat_shughl':
                var darar    = parseFloat(state.nizaat_darar)    || 0;
                var ikhtar   = parseFloat(state.nizaat_ikhtar)   || 0;
                var otla     = parseFloat(state.nizaat_otla)     || 0;
                var aqdamiya = parseFloat(state.nizaat_aqdamiya) || 0;
                return round2(darar + ikhtar + otla + aqdamiya);

            default:
                if (mri > 0 || mty > 0) {
                    return round2(mri + mty);
                }
                return round2(mi);
        }
    }

    function rasmQadai(montant) {
        var m = parseFloat(montant) || 0;
        if (m <= 0)     return 0;
        if (m <= 5000)  return round2(m * 0.04);
        if (m <= 20000) return round2(m * 0.025);
        return round2(m * 0.01 + 300);
    }

    function buildBreakdown(state) {
        var type         = state.type_cas || 'autre';
        var montantPour  = montantPourRasm(state);  // المجموع (base pour الرسم القضائي)
        var rasm         = rasmQadai(montantPour);
        var expertise    = parseFloat(state.expertise) || 0;
        var rusumMurafaa = 10;
        var rasmBahth    = type === 'gharama_ijbariya' ? 0 : 20;
        var janaza       = (type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal')
                            ? parseFloat(state.masarif_janaza) || 0
                            : 0;

        var taawidat      = parseFloat(state.montant_taawidat)       || 0;
        var masarifTibiya = parseFloat(state.montant_masarif_tibiya) || 0;

        // BUG FIX: المبلغ المؤدى = frais judiciaires SEULEMENT (sans janaza)
        // janaza est déjà dans المجموع, elle ne doit pas s'ajouter au total des frais
        var total = round2(rasm + rusumMurafaa + rasmBahth + expertise);

        // ── montant affiché dans la ligne "المبلغ" / "مجموع المستفيدين" ──
        var montantAfficheOriginal;

        if (type === 'nizaat_shughl') {
            var darar    = parseFloat(state.nizaat_darar)    || 0;
            var ikhtar   = parseFloat(state.nizaat_ikhtar)   || 0;
            var otla     = parseFloat(state.nizaat_otla)     || 0;
            var aqdamiya = parseFloat(state.nizaat_aqdamiya) || 0;
            montantAfficheOriginal = round2(darar + ikhtar + otla + aqdamiya);

        } else if (type === 'masdar_total_taawidat') {
            montantAfficheOriginal = round2(
                (parseFloat(state.montant_rasemal_ijmali) || 0) +
                (parseFloat(state.montant_taawidat_youmiya) || 0)
            );
        } else if (type === 'wafaya_irad_omri') {
            // Afficher uniquement la somme des bénéficiaires (sans janaza)
            montantAfficheOriginal = sommeBeneficiaires(state.beneficiaires_json, true);
        } else if (type === 'wafaya_ras_mal') {
            // Afficher uniquement la somme des bénéficiaires (sans janaza)
            montantAfficheOriginal = sommeBeneficiaires(state.beneficiaires_json, false);
        } else {
            montantAfficheOriginal = round2(parseFloat(state.montant_initial) || 0);
        }

        // ── المجموع ──
        var majmou;
        if (type === 'nizaat_shughl') {
            majmou = montantAfficheOriginal;
        } else if (type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal') {
            // BUG FIX: المجموع = مجموع المستفيدين + مصاريف الجنازة
            majmou = round2(montantAfficheOriginal + janaza);
        } else {
            majmou = round2(montantAfficheOriginal + taawidat + masarifTibiya + janaza);
        }

        return {
            montant               : montantAfficheOriginal,
            montant_taawidat      : taawidat,
            montant_masarif_tibiya: masarifTibiya,
            masarif_janaza        : round2(janaza),
            montant_original      : majmou,          // المجموع
            rasm_qadai            : rasm,             // 2.5% × majmou
            rusum_murafaa         : rusumMurafaa,
            rasm_bahth            : rasmBahth,
            expertise             : round2(expertise),
            total                 : total,            // المبلغ المؤدى (frais seulement)
            type_cas              : type,
            montant_pour_rasm     : montantPour,
            // champs nizaat exposés pour fillBreakdownTable
            nizaat_darar          : parseFloat(state.nizaat_darar)    || 0,
            nizaat_ikhtar         : parseFloat(state.nizaat_ikhtar)   || 0,
            nizaat_otla           : parseFloat(state.nizaat_otla)     || 0,
            nizaat_aqdamiya       : parseFloat(state.nizaat_aqdamiya) || 0,
        };
    }

    function formatMoney(n) {
        return (Number(n) || 0).toLocaleString('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    global.DossierCalc = {
        buildBreakdown  : buildBreakdown,
        formatMoney     : formatMoney,
        montantPourRasm : montantPourRasm,
        rasmQadai       : rasmQadai,
    };

})(typeof window !== 'undefined' ? window : globalThis);