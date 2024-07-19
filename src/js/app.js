$(function show() {
    /*PROFIL*/
    /*updat profil*/
    $("#bt_updat_user").click(function (e) {
        e.preventDefault()
        var form = document.querySelectorAll("form#user_data div input")
        var updatForm = "";
        updatForm += `
        <div class="updatForm">    
        <form method="POST" action="updatUser" id="updatUserForm">
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Level</span>
                <input type="number" name="user_level" value="${form[0].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Email</span>
                <input type="email" name="user_level" value="${form[1].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Name</span>
                <input type="text" name="user_fname" value="${form[2].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Lastname</span>
                <input type="text" name="user_lname" value="${form[3].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Age</span>
                <input type="number" name="user_age" value="${form[4].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Sexe</span>
                <select class="form-control" name="user_sexe" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"">
                    <option value="1" selected>Masculin</option>
                    <option value="2">Feminin</option>
                </select>
            </div>
            <div>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User ID</span>
                <input type="number" name="user_id" value="${form[6].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Description</span>
                <input type="text" name="user_description" value="${form[7].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Adresse</span>
                <input type="text" name="user_adress" value="${form[8].value}" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="input-group mb-2">
                <input type="submit" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default" name="updatProfilData" value="Validate">
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <button type="button" class="btn btn-danger" id="updatProfilCloser">Close</button>
                    </div>
            </div>
      </form>
      </div>
            `;

        var layout = document.querySelector('.layout')
        layout.classList.toggle("d-none")
        layout.innerHTML = updatForm;

        //bt form updat closer
        $("#updatProfilCloser").click(function (e) {
            $("#bt_updat_user").trigger("click");
        })
    });

    /*CREATE ACCOUNT*/
    $(".createAccount").click(function (e) {
        createForm = `
        <div class="updatForm">
        <div class="addbooks">Create account</div>   
        <form method="POST" action="createtUser" id="createUserForm">
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Email</span>
                <input type="email" name="user_email" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Password</span>
                <input type="password" name="user_password" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
            </div>
            <div class="input-group mb-2">
                <input type="submit" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default" name="createUserData" value="Create account">
            </div>
      </form>
      </div>
            `;

        var layout = document.querySelector('.layout')
        layout.classList.toggle("d-none")
        layout.innerHTML = createForm;
    })

    /*LIBRARY*/
    /*search box*/
    var searchBoxForm = "";
    searchBoxForm = `
    <div class="searchBook">
        <div class="addbooks">Search books</div>   
        <form method="POST" action="searchBookRequest" id="searchBook">
        <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Choose Item</span>
                <select class="form-control" name="item_type" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"">
                    <option value="title" selected>Title</option>
                    <option value="author">Author</option>
                    <option value="gender">Gender</option>
                </select>
            </div>    
        <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Item Value</span>
                <input type="text" name="item_value" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
            </div>
            <div class="input-group mb-2">
                <input type="submit" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default" name="searchBook" value="Search">
            </div>
        </form>
    </div>
    `;
    $(".books_search_box").html(searchBoxForm);

    /*available vs borrowed books*/
    var availBorrowBook = "";
    availBorrowBook = `
    <div class="searchBook">  
        <form method="POST" action="availBorrowBook" id="availBorrowBook">
            <input type="submit" class="form-control bg-primary" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="availableBook" value="availableBook">
        </form>
        <form method="POST" action="notAvailBorrowBook" id="notAvailBorrowBook">
            <input type="submit" class="form-control bg-warning" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="notAvailBorrowBook" value="Borrowed">
        </form>
    </div>
    `;
    $(".availBorrowBookBox").html(availBorrowBook);
})