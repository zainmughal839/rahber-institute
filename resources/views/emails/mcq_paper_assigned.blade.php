<!DOCTYPE html>
<html>
<head>
    <title>MCQ Paper Available</title>
</head>
<body>
    <h2>Dear {{ $studentName }},</h2>

    <p>A new MCQ paper has been assigned to you.</p>

    <h3>Paper Details:</h3>
    <ul>
        <li><strong>Title:</strong> {{ $paperTitle }}</li>
        <li><strong>Date:</strong> {{ $paperDate }}</li>
        <li><strong>Total Time:</strong> {{ $paper->per_mcqs_time }} minutes</li>
        <li><strong>Marks per MCQ:</strong> {{ $paper->marks_per_mcq }}</li>
    </ul>

    <p>You can attempt the paper within 24 hours from the paper date.</p>

    <p style="text-align:center;">
        <a href="{{ $link }}" style="background:#28a745;color:white;padding:12px 30px;text-decoration:none;border-radius:6px;font-size:16px;">
            Attempt Paper Now
        </a>
    </p>

    <p>Good luck!</p>
    <br>
    <p>Regards,<br>Rahber Institute of Medical Sciences</p>
</body>
</html>