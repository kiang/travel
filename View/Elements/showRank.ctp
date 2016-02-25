<?php

if (!empty($showRank)) {
    $iconSpan = '<span class="star-rating-control">';
    for ($i = 1; $i <= 5; $i++) {
        $iconSpan .= '<div class="star-rating rater-0 star star-rating-applied star-rating-live';
        if ($showRank < $i) {
            $iconSpan .= ' star-rating-off';
        } else {
            $iconSpan .= ' star-rating-on';
        }
        $iconSpan .= '"><a title="' . $showRank . '"></a></div>';
    }
    $iconSpan .= '</span>';
    echo $iconSpan;
}