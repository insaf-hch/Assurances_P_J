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

            case 'masdar_total_taawidat':
                var somme = round2(mri + mty);
                return somme > 0 ? somme : round2(mi);

            case 'gharama_ijbariya':
                return round2(mi);

            case 'wafaya_irad_omri':
                return sommeBeneficiaires(ben, true);

            case 'wafaya_ras_mal':
                return sommeBeneficiaires(ben, false);

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
        var montantPour  = montantPourRasm(state);
        var rasm         = rasmQadai(montantPour);
        var expertise    = parseFloat(state.expertise) || 0;
        var rusumMurafaa = 10;
        var rasmBahth    = type === 'gharama_ijbariya' ? 0 : 20;
        var janaza       = (type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal')
                            ? parseFloat(state.masarif_janaza) || 0
                            : 0;

        var total = round2(rasm + rusumMurafaa + rasmBahth + expertise + janaza);

        var montantAfficheOriginal;
        if (type === 'masdar_total_taawidat') {
            montantAfficheOriginal = round2(
                (parseFloat(state.montant_rasemal_ijmali) || 0) +
                (parseFloat(state.montant_taawidat_youmiya) || 0)
            );
        } else if (type === 'wafaya_irad_omri' || type === 'wafaya_ras_mal') {
            montantAfficheOriginal = montantPour;
        } else {
            montantAfficheOriginal = round2(parseFloat(state.montant_initial) || 0);
        }

        return {
            montant_pour_rasm : montantPour,
            montant_original  : montantAfficheOriginal,
            rasm_qadai        : rasm,
            rusum_murafaa     : rusumMurafaa,
            rasm_bahth        : rasmBahth,
            expertise         : round2(expertise),
            masarif_janaza    : round2(janaza),
            total             : total,
            type_cas          : type,
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