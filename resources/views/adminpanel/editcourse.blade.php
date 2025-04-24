<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin-safetyfirstHUB</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('adminassets/css/course-form.css') }}" rel="stylesheet" type="text/css">



    <style>
        .loading-state {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #submitBtn:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>

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
                    <h1 class="h3 mb-4 text-gray-800">Edit Course</h1>

                    <form action="{{ route('update.course', $course->id) }}" method="POST" enctype="multipart/form-data" class="course-form">
                        @csrf

                        <div class="row">
                            <!-- LEFT -->
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header"><h4 class="m-0 font-weight-bold text-primary">Basic Course Information</h4></div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>📌 Course Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $course->name }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>📁 Category</label>
                                            <input type="text" name="category" class="form-control" value="{{ $course->category }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>💰 Price</label>
                                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $course->price }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>📝 Short Description</label>
                                            <textarea name="short_description" class="form-control" required>{{ $course->short_description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>📄 Full Description</label>
                                            <textarea name="description" class="form-control" rows="3" required>{{ $course->description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>📆 Duration (Months)</label>
                                            <input type="number" name="duration" class="form-control" min="1" value="{{ $course->duration }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>🖼 Cover Image (Leave empty to keep old)</label>
                                            <input type="file" name="cover" class="form-control" accept="image/*">
                                            <div class="mt-2"><img src="{{ asset('storage/'.$course->cover) }}" width="120"></div>
                                        </div>

                                        <div class="form-group">
                                            <label>🎥 Total Videos</label>
                                            <input type="number" name="total_videos" class="form-control" value="{{ $course->total_videos }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT : SECTIONS -->
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header"><h4 class="m-0 font-weight-bold text-primary">Course Sections</h4></div>
                                    <div class="card-body">
                                        <div id="sections">
                                            @foreach($course->sections as $sectionIndex => $section)
                                                <div class="accordion-section" data-section-index="{{ $sectionIndex }}">
                                                    <div class="accordion-header">
                                                        <div class="d-flex justify-content-between align-items-center w-100">
                                                            <div class="section-header">
                                                                <label><span class="accordion-toggle">▼</span> Section Title</label>

                                                                <!-- Hidden ID Section -->
                                                                <input type="hidden" name="sections[{{ $sectionIndex }}][id]" value="{{ $section->id }}">

                                                                <input type="text" name="sections[{{ $sectionIndex }}][title]" class="form-control" value="{{ $section->title }}" required>
                                                            </div>
                                                            <button type="button" class="btn-icon remove-section-btn"><i class="fas fa-trash-alt text-danger"></i></button>
                                                        </div>
                                                    </div>

                                                    <div class="accordion-content">
                                                        <!-- SLIDES -->
                                                        <div class="sub-accordion">
                                                            <div class="sub-accordion-header main-sub-header d-flex justify-content-between align-items-center">
                                                                <h4 class="text-primary mb-0">📑 Slides</h4>
                                                                <button type="button" class="add-slide-btn btn btn-sm btn-success">➕ Add Slide</button>
                                                            </div>
                                                            <div class="slides-wrapper">
                                                                @foreach($section->slides as $slideIndex => $slide)
                                                                    <div class="slide-accordion" data-slide-index="{{ $slideIndex }}">
                                                                        <div class="sub-accordion-header d-flex justify-content-between align-items-center">
                                                                            <h5 class="mb-0"><span class="accordion-toggle">▼</span> 📑 Slide {{ $slideIndex + 1 }}</h5>
                                                                            <button type="button" class="btn-icon remove-slide-btn"><i class="fas fa-trash-alt text-danger"></i></button>
                                                                        </div>
                                                                        <div class="sub-accordion-content">
                                                                            <!-- Hidden Slide ID -->
                                                                            <input type="hidden" name="sections[{{ $sectionIndex }}][slides][{{ $slideIndex }}][id]" value="{{ $slide->id }}">

                                                                            <div class="form-group">
                                                                                <label>📝 Slide Title</label>
                                                                                <input type="text" name="sections[{{ $sectionIndex }}][slides][{{ $slideIndex }}][title]" class="form-control" value="{{ $slide->title }}" required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>🖊 Slide Content</label>
                                                                                <textarea name="sections[{{ $sectionIndex }}][slides][{{ $slideIndex }}][content]" class="form-control">{{ $slide->content }}</textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>📎 Attach PDF/Image (Leave empty to keep old)</label>
                                                                                <input type="file" name="sections[{{ $sectionIndex }}][slides][{{ $slideIndex }}][file]" class="form-control">
                                                                                @if($slide->file)<div class="mt-2"><a href="{{ asset('storage/'.$slide->file) }}" target="_blank">Old File</a></div>@endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <!-- VIDEOS -->
                                                        <div class="sub-accordion mt-3">
                                                            <div class="sub-accordion-header main-sub-header d-flex justify-content-between align-items-center">
                                                                <h4 class="text-primary mb-0">🎥 Videos</h4>
                                                                <button type="button" class="add-video-btn btn btn-sm btn-success">➕ Add Video</button>
                                                            </div>
                                                            <div class="videos-wrapper">
                                                                @foreach($section->videos as $videoIndex => $video)
                                                                    <div class="video-accordion" data-video-index="{{ $videoIndex }}">
                                                                        <div class="sub-accordion-header d-flex justify-content-between align-items-center">
                                                                            <h5 class="mb-0"><span class="accordion-toggle">▼</span> 🎥 Video {{ $videoIndex + 1 }}</h5>
                                                                            <button type="button" class="btn-icon remove-video-btn"><i class="fas fa-trash-alt text-danger"></i></button>
                                                                        </div>
                                                                        <div class="sub-accordion-content">
                                                                            <!-- Hidden Video ID -->
                                                                            <input type="hidden" name="sections[{{ $sectionIndex }}][videos][{{ $videoIndex }}][id]" value="{{ $video->id }}">

                                                                            <div class="form-group">
                                                                                <label>🎞 Video Title</label>
                                                                                <input type="hidden" name="sections[{{ $sectionIndex }}][videos][{{ $videoIndex }}][id]" value="{{ $video->id }}">

                                                                                <input type="text" name="sections[{{ $sectionIndex }}][videos][{{ $videoIndex }}][title]" class="form-control" value="{{ $video->title }}" required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>📤 Upload Video (Leave empty to keep old)</label>
                                                                                <input type="file" name="sections[{{ $sectionIndex }}][videos][{{ $videoIndex }}][video]" class="form-control">
                                                                                @if($video->video)<div class="mt-2"><a href="{{ asset('storage/'.$video->video) }}" target="_blank">Old Video</a></div>@endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button" id="add-section-btn" class="btn btn-success mt-3">➕ Add New Section</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">💾 Update Course</button>
                            </div>
                        </div>
                    </form>

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




</body>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- Bootstrap Bundle Min JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminassets/js/sb-admin-2.min.js') }}"></script>


    <script>


        document.addEventListener("DOMContentLoaded", function() {
            let sectionCounter = document.querySelectorAll(".accordion-section").length;


        if (document.querySelectorAll(".accordion-section").length === 0) {
            const firstSection = createAccordionSection(0);
            document.getElementById("sections").innerHTML = firstSection;
        }

        function createSlideAccordion(sectionIndex, slideIndex) {
            return `
                <div class="slide-accordion" data-slide-index="${slideIndex}">
                    <div class="sub-accordion-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><span class="accordion-toggle">▼</span> 📑 Slide ${slideIndex + 1}</h5>
                            <div>

                                <button type="button" class="btn-icon remove-slide-btn">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="sub-accordion-content">
                        <div class="form-group">
                            <label>📝 Slide Title</label>
                            <input type="text" name="sections[${sectionIndex}][slides][${slideIndex}][title]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>🖊 Slide Content</label>
                            <textarea name="sections[${sectionIndex}][slides][${slideIndex}][content]" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>📎 Attach PDF/Image</label>
                            <input type="file" name="sections[${sectionIndex}][slides][${slideIndex}][file]" accept="application/pdf,image/*" class="form-control">
                        </div>
                    </div>
                </div>
            `;
        }

        function createVideoAccordion(sectionIndex, videoIndex) {
            return `
                <div class="video-accordion" data-video-index="${videoIndex}">
                    <div class="sub-accordion-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><span class="accordion-toggle">▼</span> 🎥 Video ${videoIndex + 1}</h5>
                            <div>

                                <button type="button" class="btn-icon remove-video-btn">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="sub-accordion-content">
                        <div class="form-group">
                            <label>🎞 Video Title</label>
                            <input type="text" name="sections[${sectionIndex}][videos][${videoIndex}][title]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>📤 Upload Video</label>
                            <input type="file" name="sections[${sectionIndex}][videos][${videoIndex}][video]" accept="video/*" class="form-control">
                        </div>
                    </div>
                </div>
            `;
        }

        function createAccordionSection(sectionIndex) {
            return `
                <div class="accordion-section" data-section-index="${sectionIndex}">
                    <div class="accordion-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="section-header">
                                <label><span class="accordion-toggle">▼</span> Section Title</label>
                                <input type="text" name="sections[${sectionIndex}][title]" class="form-control" required>
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn-icon remove-section-btn">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-content">
                        <!-- Slides Container -->
                        <div class="sub-accordion">
                            <div class="sub-accordion-header main-sub-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="text-primary mb-0">📑 Slides</h4>
                                    <button type="button" class="add-slide-btn btn btn-sm btn-success">➕ Add Slide</button>
                                </div>
                            </div>
                            <div class="slides-wrapper">
                                ${createSlideAccordion(sectionIndex, 0)}
                            </div>
                        </div>

                        <!-- Videos Container -->
                        <div class="sub-accordion">
                            <div class="sub-accordion-header main-sub-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="text-primary mb-0">🎥 Videos</h4>
                                    <button type="button" class="add-video-btn btn btn-sm btn-success">➕ Add Video</button>
                                </div>
                            </div>
                            <div class="videos-wrapper">
                                ${createVideoAccordion(sectionIndex, 0)}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        document.getElementById("add-section-btn").addEventListener("click", function() {
            sectionCounter++;
            const newSection = createAccordionSection(sectionCounter);
            document.getElementById("sections").insertAdjacentHTML('beforeend', newSection);
        });

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("add-slide-btn")) {
                const section = e.target.closest(".accordion-section");
                const sectionIndex = section.dataset.sectionIndex;
                const slidesWrapper = section.querySelector(".slides-wrapper");
                const slideIndex = slidesWrapper.querySelectorAll(".slide-accordion").length;

                const newSlide = createSlideAccordion(sectionIndex, slideIndex);
                slidesWrapper.insertAdjacentHTML('beforeend', newSlide);
            }
        });

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("add-video-btn")) {
                const section = e.target.closest(".accordion-section");
                const sectionIndex = section.dataset.sectionIndex;
                const videosWrapper = section.querySelector(".videos-wrapper");
                const videoIndex = videosWrapper.querySelectorAll(".video-accordion").length;

                const newVideo = createVideoAccordion(sectionIndex, videoIndex);
                videosWrapper.insertAdjacentHTML('beforeend', newVideo);
            }
        });

        // Update the toggle accordion handlers
        document.addEventListener("click", function(e) {
            // Toggle for main section accordion
            if (e.target.classList.contains("accordion-toggle") || e.target.closest(".accordion-toggle")) {
                const header = e.target.closest(".accordion-header") || e.target.closest(".sub-accordion-header");
                const content = header.nextElementSibling;

                header.classList.toggle("active");
                content.classList.toggle("show");
            }

            // For clicking anywhere on the header (optional)
            if (e.target.closest('.accordion-header')) {
                const header = e.target.closest('.accordion-header');
                const content = header.nextElementSibling;

                header.classList.toggle('active');
                content.classList.toggle('show');
            }

            if (e.target.closest('.sub-accordion-header:not(.main-sub-header)')) {
                const header = e.target.closest('.sub-accordion-header');
                const content = header.nextElementSibling;

                header.classList.toggle('active');
                content.classList.toggle('show');
            }
        });

        // Add remove handlers
        document.addEventListener("click", function(e) {
            // Remove section
            if (e.target.classList.contains("remove-section-btn") || e.target.closest(".remove-section-btn")) {
                const section = e.target.closest(".accordion-section");
                if (document.querySelectorAll(".accordion-section").length > 1) {
                    section.remove();
                } else {
                    alert("You cannot remove the last section!");
                }
            }

            // Remove slide
            if (e.target.classList.contains("remove-slide-btn") || e.target.closest(".remove-slide-btn")) {
                const slide = e.target.closest(".slide-accordion");
                const slidesWrapper = slide.closest(".slides-wrapper");
                    slide.remove();

            }

            // Remove video
            if (e.target.classList.contains("remove-video-btn") || e.target.closest(".remove-video-btn")) {
                const video = e.target.closest(".video-accordion");
                const videosWrapper = video.closest(".videos-wrapper");

                    video.remove();

            }
        });
        });

        document.querySelector('.course-form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const normalState = submitBtn.querySelector('.normal-state');
            const loadingState = submitBtn.querySelector('.loading-state');

            // Disable button and show loading state
            submitBtn.disabled = true;
            normalState.classList.add('d-none');
            loadingState.classList.remove('d-none');
        });
    </script>

</html>

