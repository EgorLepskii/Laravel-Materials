document.querySelectorAll('.delete-tag-link').forEach(function (e){
    e.addEventListener('click', function (){
        let status = confirm('Подтвердите удаление');

        if (!status) return;
        let entryid = e.dataset.entryid;
        let materialId = e.dataset.materialid;


        let data = entryid;

        let uri = "tagManage/destroy";
        let redirectUri = "/material/" + materialId;


        let xhr = sendRequest(window.location.origin + `/${uri}?entryid=${entryid}`,'delete');


        xhr.onreadystatechange = function ()
        {
            if (xhr.status === 200)
            {
                window.location.replace(window.location.origin+redirectUri);
            }
        }
    });
});
