document.querySelectorAll('.delete-link').forEach(function (e){
    e.addEventListener('click', function (){
       let status = confirm('Подтвердите удаление');

       if (!status) return;
       let id = e.dataset.id;
       let type = e.dataset.type;
       let uri = "";
       let redirectUri = "";

       switch (type) {
           case "tag":
               uri = "/tag/destroy"
               redirectUri = "/tag"

               break;
           case "category":
               uri = "/category/destroy"
               redirectUri = "/category"

               break;
           case "material":
               uri = "/material/destroy"
               redirectUri = "/material"
               break;

       }

        let xhr = sendRequest(window.location.origin + `${uri}/${id}`,'delete','');

       xhr.onreadystatechange = function ()
       {
           if (xhr.status === 200)
           {
               window.location.replace(window.location.origin+redirectUri);
           }
       }
    });
});
