$('#type_package').autocomplete({
    source: function (request, response) {
        var result = [];
        var limit = 10;
        var term = request.term.toLowerCase();
        $.each(_opts.package, function () {
            var package = this;
            if (term == '' || package.type_package.toLowerCase().indexOf(term) >= 0 ||
                (package.type_package && package.type_package.toLowerCase().indexOf(term) >= 0) ||
                (package.type_package && package.type_package.toLowerCase().indexOf(term) >= 0)) {
                result.push(package);
                limit--;
                if (limit <= 0) {
                    return false;
                }
            }
        });
        response(result);
    },
    focus: function (event, ui) {
        $('#type_package').val(ui.item.type_package);
        return false;
    },
    select: function (event, ui) {
        $('#type_package').val(ui.item.type_package);
        $('#type_package_id').val(ui.item.id);
        return false;
    },
    search: function () {
        $('#type_package_id').val('');
    }
}).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append($('<a>').append($('<b>').text(item.name)).append('<br>')
            .append($('<i>').text(item.parent_name + ' | ' + item.route)))
        .appendTo(ul);
};

