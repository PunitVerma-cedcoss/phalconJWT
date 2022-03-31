$(".radio").click(function (e) {
    if ($(".rolename").val().trim()) {
        if ($(this).is(':checked')) {
            $(this).parent().append(`<span class="mx-2 bg-indigo-400 shadow-lg text-white px-3 my-1 rounded-full flex justify-start items-center"><i class="mr-2 fa fa-angle-left"></i> assigned to ${$(".rolename").val().trim()}</span>`)
        }
        else {
            $(this).next().next().remove()
        }
    }
    else {
        alert("enter the role name first")
        e.preventDefault()
    }
});

$(".radio:checked").click(function (e) {
    alert("clicked")
});