<script>
    $(function(){
        new Readmore('.show-read-more', {
            collapsedHeight: 48,
            speed: 500,
            moreLink: '<a href="#">' + '{{ trans("main.Read More") }}' + '</a>',
            lessLink: '<a href="#">' + '{{ trans("main.Read Less") }}' + '</a>',
            {{-- afterToggle: function(trigger, element, expanded) {
                if(!expanded) {
                    window.scrollTo({top: element.offsetTop, behavior: 'smooth'})
                }
            }, --}}
        });
    });
</script>