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
        var mi   = parseFloat(state.montant_initial) || 0;
        var mri  = parseFloat(state.montant_rasemal_ijmali) || 0;
        var mty  = parseFloat(state.montant_taawidat_youmiya) || 0;
        var ben  = state.beneficiaires_json;

        switch (type) {

          case 'irad_omri': {
    var tib = parseFloat(state.montant_masarif_tibiya) || 0;  // montant_masarif_tibiya
    var tyd = parseFloat(state.montant_taawidat_youmiya) || 0; // montant_taawidat_youmiya
    var taa = parseFloat(state.montant_taawidat) || 0;         // montant_taawidat ✅
    return round2((mi * 10) + tib + tyd + taa);
}

case 'irad_omri_ras_mal': {
    var tib = parseFloat(state.montant_masarif_tibiya) || 0;   // montant_masarif_tibiya
    var tyd = parseFloat(state.montant_taawidat_youmiya) || 0; // montant_taawidat_youmiya
    var taa = parseFloat(state.montant_taawidat) || 0;         // montant_taawidat ✅
    return round2(mi + tib + tyd + taa);
}

            case 'masdar_total_taawidat': {
                var tib  = parseFloat(state.montant_masarif_tibiya) || 0;
                var taa  = parseFloat(state.montant_taawidat) || 0;
                var tyd  = parseFloat(state.montant_taawidat_youmiya) || 0;
                return round2(mi + tib + taa + tyd);
            }

            case 'gharama_ijbariya':
                return round2(mi);

            case 'wafaya_irad_omri': {
                var benSum = sommeBeneficiaires(ben, true);
                var janaza = parseFloat(state.masarif_janaza) || 0;
                return round2(benSum + janaza);
            }

            case 'wafaya_ras_mal': {
                var benSum = sommeBeneficiaires(ben, false);
                var janaza = parseFloat(state.masarif_janaza) || 0;
                return round2(benSum + janaza);
            }

            case 'nizaat_shughl': {
                var darar    = parseFloat(state.nizaat_darar)    || 0;
                var ikhtar   = parseFloat(state.nizaat_ikhtar)   || 0;
                var otla     = parseFloat(state.nizaat_otla)     || 0;
                var aqdamiya = parseFloat(state.nizaat_aqdamiya) || 0;
                return round2(darar + ikhtar + otla + aqdamiya);
            }

            default: {
    var taa = parseFloat(state.montant_taawidat) || 0;
    var tib = parseFloat(state.montant_masarif_tibiya) || 0;
    var mty = parseFloat(state.montant_taawidat_youmiya) || 0;
    var mri = parseFloat(state.montant_rasemal_ijmali) || 0;
    if (mri > 0) {
        return round2(mri + mty + taa + tib);
    }
    // ✅ يجمع كل التعويضات
    return round2(mi + taa + mty + tib);
}
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
        var type        = state.type_cas || 'autre';
        var montantPour = montantPourRasm(state);
        var rasm        = rasmQadai(montantPour);

        var mi           = parseFloat(state.montant_initial) || 0;
        var expertise    = parseFloat(state.expertise) || 0;
        var rusumMurafaa = 10;
        var rasmBahth    = type === 'gharama_ijbariya' ? 0 : 20;
        var janaza       = (type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal')
                            ? parseFloat(state.masarif_janaza) || 0
                            : 0;
        var taawidat      = parseFloat(state.montant_taawidat) || 0;
        var masarifTibiya = parseFloat(state.montant_masarif_tibiya) || 0;
        var taawidatYoumiya = parseFloat(state.montant_taawidat_youmiya) || 0;
        
        var total = round2( rasm + rusumMurafaa + rasmBahth + expertise);

        

        // ── المبلغ المعروض في السطر الأول ──
        var montantAffiche;
        var majmou;

        if (type === 'nizaat_shughl') {
            var darar    = parseFloat(state.nizaat_darar)    || 0;
            var ikhtar   = parseFloat(state.nizaat_ikhtar)   || 0;
            var otla     = parseFloat(state.nizaat_otla)     || 0;
            var aqdamiya = parseFloat(state.nizaat_aqdamiya) || 0;
            montantAffiche = round2(darar + ikhtar + otla + aqdamiya);
            majmou         = montantAffiche;

} else if (type === 'irad_omri') {
    montantAffiche = round2(mi * 10);
    majmou = round2(montantAffiche + masarifTibiya + taawidatYoumiya + taawidat); // ✅ + taawidat

} else if (type === 'irad_omri_ras_mal') {
    montantAffiche = round2(mi);
    majmou = round2(montantAffiche + masarifTibiya + taawidatYoumiya + taawidat); // ✅ + taawidat
        } else if (type === 'masdar_total_taawidat') {
            montantAffiche = mi;
            majmou         = round2(mi + taawidat + masarifTibiya + taawidatYoumiya);

        } else if (type === 'wafaya_irad_omri') {
            montantAffiche = sommeBeneficiaires(state.beneficiaires_json, true);
            majmou         = round2(montantAffiche + janaza);

        } else if (type === 'wafaya_ras_mal') {
            montantAffiche = sommeBeneficiaires(state.beneficiaires_json, false);
            majmou         = round2(montantAffiche + janaza);

        } else {
            // autre/default
            var mri = parseFloat(state.montant_rasemal_ijmali) || 0;
            var mty = parseFloat(state.montant_taawidat_youmiya) || 0;
            if (mri > 0 || mty > 0) {
                montantAffiche = round2(mri + mty + taawidat + masarifTibiya);
            } else {
                montantAffiche = round2(mi + taawidat + masarifTibiya);
            }
            majmou = montantAffiche; // ✅ لا مضاعفة
        }

        return {
            montant               : montantAffiche,
            montant_taawidat      : taawidat,
            montant_masarif_tibiya: masarifTibiya,
            masarif_janaza        : round2(janaza),
            montant_original      : majmou,
            rasm_qadai            : rasm,
            rusum_murafaa         : rusumMurafaa,
            rasm_bahth            : rasmBahth,
            expertise             : round2(expertise),
            total                 : total,
            type_cas              : type,
            montant_pour_rasm     : montantPour,
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