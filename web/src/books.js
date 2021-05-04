

function getBookContent(books) {
    // A book has been selected, show it's information
    var content = $("#item_content").append(`
        <div class="row">
            <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                <h1 class="mb-3">` + books.data[0].name + `</h1>
                <p class="lead">` + books.data[0].summary + `</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-11 px-lg-5 px-md-3">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `);
}