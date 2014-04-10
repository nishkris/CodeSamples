function get_models() {
    var e = document.getElementById("select_brand");
    var brand = e.options[e.selectedIndex].text;
    $.ajax({
        type:"POST",
        url:"get_models.php",
        data:{brand:brand}
    }).done(function (result) {
            var data_array = $.parseJSON(result);
            e = document.getElementById("select_model");
            var count = $('#select_model option').size();
            // remove all except 1st element -- Model --
            for (i = 1; i < count; i++) {
                e.remove(1);
            }

            for (i = 0; i < data_array.length; i++) {
                var option = document.createElement("option");
                option.text = data_array[i];
                e.add(option, null);
            }
            e.options[0].selected = true;
            e.disabled = false;
            document.getElementById("select_trim").options[0].selected = true;
        });
}

function get_trims() {
    var e = document.getElementById("select_brand");
    var brand = e.options[e.selectedIndex].text;
    e = document.getElementById("select_model");
    var model = e.options[e.selectedIndex].text;
    $.ajax({
        type:"POST",
        url:"get_trims.php",
        data:{brand:brand, model:model}
    }).done(function (result) {
            var data_array = $.parseJSON(result);
            e = document.getElementById("select_trim");
            var count = $('#select_trim option').size();
            // remove all except 1st element -- Trim --
            for (i = 1; i < count; i++) {
                e.remove(1);
            }

            for (i = 0; i < data_array.length; i++) {
                var option = document.createElement("option");
                option.text = data_array[i];
                e.add(option, null);
            }
            e.options[0].selected = true;
            e.disabled = false;
        });
}
