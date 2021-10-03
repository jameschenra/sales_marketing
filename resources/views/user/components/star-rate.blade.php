@for ($i = 0; $i < $score; $i++)
    <span class="fas fa-star text-warning"></span>
@endfor

@for ($i = $score; $i < 5; $i++)
    <span class="far fa-star text-dark-50"></span>
@endfor