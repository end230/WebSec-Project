<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizSubmission;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function submit(Request $request, Quiz $quiz)
    {
        if (!auth()->user()->hasPermissionTo('take_quiz')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if student has already submitted
        $existingSubmission = QuizSubmission::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->first();
            
        if ($existingSubmission) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'You have already submitted an answer for this quiz.');
        }
        
        $validated = $request->validate([
            'answer_text' => 'required|string',
        ]);
        
        QuizSubmission::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'answer_text' => $validated['answer_text'],
            'status' => 'pending',
        ]);
        
        return redirect()->route('quizzes.index')
            ->with('success', 'Your answer has been submitted successfully and is pending review.');
    }
    
    public function grade(Request $request, QuizSubmission $submission)
    {
        if (!auth()->user()->hasPermissionTo('grade_submissions')) {
            abort(403, 'Unauthorized action.');
        }
        
        $quiz = $submission->quiz;
        
        if (auth()->id() !== $quiz->instructor_id) {
            abort(403, 'You do not have permission to grade this submission.');
        }
        
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'instructor_feedback' => 'nullable|string',
        ]);
        
        $submission->update([
            'score' => $validated['score'],
            'instructor_feedback' => $validated['instructor_feedback'],
            'status' => 'graded',
        ]);
        
        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Submission graded successfully.');
    }
    
    public function studentResults()
    {
        if (!auth()->user()->hasPermissionTo('view_own_grades')) {
            abort(403, 'Unauthorized action.');
        }
        
        $submissions = QuizSubmission::where('student_id', auth()->id())
            ->with('quiz')
            ->get();
            
        return view('quizzes.student.results', compact('submissions'));
    }
}
