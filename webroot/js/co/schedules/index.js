function schedulesIndex() {
    $('#scheduleIndexTab').tabs({
        cache: true,
        create: function() {
            $('ul', this).wrap($('<div class="block">'));
        }
    });
}