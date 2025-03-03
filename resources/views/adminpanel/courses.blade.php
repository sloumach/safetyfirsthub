<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="adminassets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="adminassets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('adminpanel.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('adminpanel.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Courses managements</h1>
                    <div class="row">

                        <div class="col-lg-6">

                            <!-- Default Card Example -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    Add new course:
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('addcourse') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label>📌 Course Name</label>
                                            <input type="text" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>📁 Category</label>
                                            <input type="text" name="category" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="number" step="0.01" class="form-control" id="price"
                                                name="price" placeholder="Enter course price" required>
                                        </div>
                                        <div class="form-group">
                                            <label>📝 Short Description</label>
                                            <textarea name="short_description" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Full Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                placeholder="Enter full course description" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>📆 Duration (Months)</label>
                                            <input type="number" name="duration" min="1" required>
                                        </div>
                                        <div class="form-group">
                                            <label>🖼 Cover Image</label>
                                            <input type="file" name="cover" accept="image/*" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_videos">Total Videos</label>
                                            <input type="number" class="form-control" id="total_videos"
                                                name="total_videos" placeholder="Enter total number of videos" required>
                                        </div>
                                        <div class="form-group">
                                            <h3>📚 Sections</h3>
                                            <div id="sections">
                                                <div class="section" data-section-index="0">
                                                    <label>🔹 Section Title</label>
                                                    <input type="text" name="sections[0][title]" required>

                                                    <!-- SLIDES -->
                                                    <div class="slides-container">
                                                        <h4>📑 Slides</h4>
                                                        <div class="slide" data-slide-index="0">
                                                            <div class="form-group">
                                                                <label>📝 Slide Title</label>
                                                                <input type="text" name="sections[0][slides][0][title]" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>🖊 Slide Content</label>
                                                                <textarea name="sections[0][slides][0][content]"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>📎 Attach PDF/Image</label>
                                                                <input type="file" name="sections[0][slides][0][file]" accept="application/pdf,image/*">
                                                            </div>
                                                        </div>
                                                        <button type="button" class="add-slide-btn">➕ Add Slide</button>
                                                    </div>

                                                    <!-- VIDEOS -->
                                                    <div class="videos-container">
                                                        <h4>🎥 Videos</h4>
                                                        <div class="video" data-video-index="0">
                                                            <div class="form-group">
                                                                <label>🎞 Video Title</label>
                                                                <input type="text" name="sections[0][videos][0][title]" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>📤 Upload Video</label>
                                                                <input type="file" name="sections[0][videos][0][video]" accept="video/*">
                                                            </div>
                                                        </div>
                                                        <button type="button" class="add-video-btn">➕ Add Video</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" id="add-section-btn">➕ Add Section</button>
                                            <button type="submit">✅ Save Course</button>
                                        </div>
                                    </form> {{-- // w9eft hne bech ntasti l'ajout de cours --}}



                                </div>
                            </div>


                        </div>
                        <div class="col-lg-6">
                            <h3>Courses List</h3>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td>{{ $course->name }}</td>
                                            <td>${{ $course->price }}</td>
                                            <td>{{ $course->category }}</td>
                                            <td>
                                                <!-- Button Edit (ouvre le modal) -->
                                                <button class="btn btn-warning btn-sm edit-btn"
                                                    data-id="{{ $course->id }}" data-name="{{ $course->name }}"
                                                    data-price="{{ $course->price }}"
                                                    data-category="{{ $course->category }}"
                                                    data-total_videos="{{ $course->total_videos }}"
                                                    data-short_description="{{ $course->short_description }}"
                                                    data-description="{{ $course->description }}">
                                                    Edit
                                                </button>

                                                <!-- Button Delete -->
                                                <form action="{{ route('delete.course', $course->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this course?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- MODAL POUR L'ÉDITION -->
                        <div class="modal fade" id="editCourseModal" tabindex="-1"
                            aria-labelledby="editCourseModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editCourseForm" action="" method="POST">
                                            @csrf
                                            <input type="hidden" id="edit-course-id" name="course_id">

                                            <div class="form-group">
                                                <label for="edit-name">Course Name</label>
                                                <input type="text" class="form-control" id="edit-name"
                                                    name="name" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="edit-price">Price</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    id="edit-price" name="price" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="edit-category">Category</label>
                                                <input type="text" class="form-control" id="edit-category"
                                                    name="category" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="edit-total-videos">Total Videos</label>
                                                <input type="number" class="form-control" id="edit-total-videos"
                                                    name="total_videos" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="edit-short-description">Short Description</label>
                                                <textarea class="form-control" id="edit-short-description" name="short_description" rows="2" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="edit-description">Full Description</label>
                                                <textarea class="form-control" id="edit-description" name="description" rows="3" required></textarea>
                                            </div>

                                            <button type="submit" class="btn btn-success">Update Course</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    {{-- <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Bootstrap core JavaScript-->
    <script src="adminassets/vendor/jquery/jquery.min.js"></script>
    <script src="adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="adminassets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="adminassets/js/sb-admin-2.min.js"></script>
    <script>
        $(document).on('click', '.edit-btn', function() {
            let courseId = $(this).data('id');
            let name = $(this).data('name');
            let price = $(this).data('price');
            let category = $(this).data('category');
            let totalVideos = $(this).data('total_videos');
            let shortDescription = $(this).data('short_description');
            let description = $(this).data('description');

            // Remplir les champs du formulaire dans le modal
            $('#edit-course-id').val(courseId);
            $('#edit-name').val(name);
            $('#edit-price').val(price);
            $('#edit-category').val(category);
            $('#edit-total-videos').val(totalVideos);
            $('#edit-short-description').val(shortDescription);
            $('#edit-description').val(description);

            // Définir l'action du formulaire avec l'ID du cours
            $('#editCourseForm').attr('action', '/admin/course/update/' + courseId);

            // Ouvrir le modal
            $('#editCourseModal').modal('show');
        });
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    let sectionCounter = document.querySelectorAll(".section").length;

    // ➕ Ajouter une nouvelle section
    document.getElementById("add-section-btn").addEventListener("click", function() {
        sectionCounter++;
        let newSection = document.querySelector(".section").cloneNode(true);

        // 🔹 Mettre à jour l'index de la section
        newSection.setAttribute("data-section-index", sectionCounter);
        newSection.innerHTML = newSection.innerHTML.replace(/\[0\]/g, `[${sectionCounter}]`);

        // 🔹 Réinitialiser les valeurs des inputs de la nouvelle section
        newSection.querySelectorAll("input, textarea").forEach(input => {
            input.value = "";
        });

        // 🔹 Réinitialiser les index des slides et vidéos de cette nouvelle section
        let slidesContainer = newSection.querySelector(".slides-container");
        slidesContainer.innerHTML = `<h4>📑 Slides</h4><button type="button" class="add-slide-btn">➕ Add Slide</button>`;

        let videosContainer = newSection.querySelector(".videos-container");
        videosContainer.innerHTML = `<h4>🎥 Videos</h4><button type="button" class="add-video-btn">➕ Add Video</button>`;

        document.getElementById("sections").appendChild(newSection);
    });

    // ➕ Ajouter un slide à une section
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("add-slide-btn")) {
            let section = event.target.closest(".section");
            let sectionIndex = section.getAttribute("data-section-index");

            let slidesContainer = section.querySelector(".slides-container");
            let slideIndex = slidesContainer.querySelectorAll(".slide").length;

            let newSlide = document.createElement("div");
            newSlide.classList.add("slide");
            newSlide.setAttribute("data-slide-index", slideIndex);
            newSlide.innerHTML = `
                <div class="form-group">
                    <label>📝 Slide Title</label>
                    <input type="text" name="sections[${sectionIndex}][slides][${slideIndex}][title]" required>
                </div>
                <div class="form-group">
                    <label>🖊 Slide Content</label>
                    <textarea name="sections[${sectionIndex}][slides][${slideIndex}][content]"></textarea>
                </div>
                <div class="form-group">
                    <label>📎 Attach PDF/Image</label>
                    <input type="file" name="sections[${sectionIndex}][slides][${slideIndex}][file]" accept="application/pdf,image/*">
                </div>
            `;
            slidesContainer.insertBefore(newSlide, event.target);
        }
    });

    // ➕ Ajouter une vidéo à une section
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("add-video-btn")) {
            let section = event.target.closest(".section");
            let sectionIndex = section.getAttribute("data-section-index");

            let videosContainer = section.querySelector(".videos-container");
            let videoIndex = videosContainer.querySelectorAll(".video").length;

            let newVideo = document.createElement("div");
            newVideo.classList.add("video");
            newVideo.setAttribute("data-video-index", videoIndex);
            newVideo.innerHTML = `
                <div class="form-group">
                    <label>🎞 Video Title</label>
                    <input type="text" name="sections[${sectionIndex}][videos][${videoIndex}][title]" required>
                </div>
                <div class="form-group">
                    <label>📤 Upload Video</label>
                    <input type="file" name="sections[${sectionIndex}][videos][${videoIndex}][video]" accept="video/*">
                </div>
            `;
            videosContainer.insertBefore(newVideo, event.target);
        }
    });
});



    </script>


</body>

</html>
