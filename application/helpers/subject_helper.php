<?php

    function isSubjectProblematic($or, $vat, $isir) {
        return isSubjectProblematicOr($or) || isSubjectProblematicVat($vat) || isSubjectProblematicIsir($isir);
    }

    function isSubjectProblematicOr($or) {
        return isSubjectProblematicLikvidace($or) || isSubjectProblematicExecutionAssociate($or);
    }

    function isSubjectProblematicVat($vat) {
        return isset($vat['unreliable']) && $vat['unreliable'];
    }

    function isSubjectProblematicIsir($isir) {
        $ci = & get_instance();

        $spis_inactive_states = $ci->config->item('STATES_NOT_INSOLVENCE');

        return isset($isir['status_id']) 
            && $isir['status_id'] !== null 
            && !in_array($isir['status_id'], $spis_inactive_states);
    }

    function isSubjectProblematicLikvidace($or) {
        return isset($or['is_likvidace']) && $or['is_likvidace'];
    }

    function isSubjectProblematicExecutionAssociate($or) {
        return isset($or['is_execution_on_associate']) && $or['is_execution_on_associate'];
    }

    function isWatchingAnything($user) {
        return $user['is_insolvencewatch'] == 1
            || $user['is_vatdebtorswatch'] == 1 
            || $user['is_likvidacewatch'] == 1
            || $user['is_accountchangewatch'] == 1
            || $user['is_claimswatch'] == 1
            || $user['is_orwatch'] == 1
            || $user['is_orskwatch'] == 1;
    }

?>