<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Certificate of Completion</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="{{ asset('assets/css/certificat.css') }}">
</head>
<body>
    <div class="certificate-preview">
        <div class="certificate-frame">
            <div class="certificate-content">
                <div class="certificate-number">
                    Certificate N°: {{ $certificateNumber ?? 'CERT-2024-001' }}
                </div>
                
                <h1 class="title">CERTIFICATE OF COMPLETION</h1>
                
                <div class="certificate-text">
                    <p class="certifies">This certifies that</p>
                    <h2 class="candidate-name">{{ $firstname }} {{ $lastname }}</h2> <!-- ici firstname et fullname -->
                    <p class="completion-text">has successfully completed the course</p>
                    <h3 class="program-name">{{ $course_name }}</h3><!-- ici course name -->
                    
                    <div class="certificate-details">
                        <div class="detail-item">
                            <img src="{{ asset('assets/img/clock.svg') }}" alt="Credit Hours" class="detail-icon">
                            <span>Credit Hours: {{ $duration }}</span> <!-- ici à vérifier avec le client -->
                        </div>
                        <div class="detail-item">
                            <img src="{{ asset('assets/img/medal.svg') }}" alt="Completion Date" class="detail-icon">
                            <span>{{ $date }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 