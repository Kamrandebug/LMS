<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionSet;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    private array $sampleQuestions = [];

    public function run(): void
    {
        $this->loadSampleQuestions();

        $sets = QuestionSet::with('topic.subject')->get();

        foreach ($sets as $set) {
            $subjectSlug = $set->topic->subject->slug;
            $topicSlug = $set->topic->slug;
            $setNumber = $set->set_number;

            // Get sample questions for this subject/topic, or generate defaults
            $topicQuestions = $this->getQuestionsForTopic($subjectSlug, $topicSlug);

            for ($i = 0; $i < 20; $i++) {
                $qIndex = $i % count($topicQuestions);
                $baseQuestion = $topicQuestions[$qIndex];
                $qNum = $i + 1;

                Question::create([
                    'question_set_id' => $set->id,
                    'qid' => "{$subjectSlug}-{$topicSlug}-s{$setNumber}-q{$qNum}",
                    'question' => str_replace('{n}', (string)$qNum, $baseQuestion['question']),
                    'option_a' => $baseQuestion['option_a'],
                    'option_b' => $baseQuestion['option_b'],
                    'option_c' => $baseQuestion['option_c'],
                    'option_d' => $baseQuestion['option_d'],
                    'correct_option' => $baseQuestion['correct_option'],
                    'explanation' => $baseQuestion['explanation'] ?? 'Review the topic material for more details.',
                    'sort_order' => $i,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function loadSampleQuestions(): void
    {
        $this->sampleQuestions = [
            'computer-science' => [
                'computer-fundamentals-hardware' => [
                    ['question' => 'Which electronic component was primarily used in the first generation of computers?', 'option_a' => 'Transistors', 'option_b' => 'Vacuum Tubes', 'option_c' => 'Integrated Circuits', 'option_d' => 'Microprocessors', 'correct_option' => 'b', 'explanation' => 'First generation computers (1940-1956) relied on vacuum tubes for circuitry.'],
                    ['question' => 'The transition from the second to the third generation of computers was marked by the invention of which technology?', 'option_a' => 'Artificial Intelligence', 'option_b' => 'Vacuum Tubes', 'option_c' => 'Integrated Circuits', 'option_d' => 'Transistors', 'correct_option' => 'c', 'explanation' => 'Third generation computers (1964-1971) used Integrated Circuits (ICs).'],
                    ['question' => 'Who is widely considered the "Father of the Computer"?', 'option_a' => 'Alan Turing', 'option_b' => 'John von Neumann', 'option_c' => 'Charles Babbage', 'option_d' => 'Blaise Pascal', 'correct_option' => 'c', 'explanation' => 'Charles Babbage designed the Analytical Engine, the first general-purpose mechanical computer.'],
                    ['question' => 'Which was the first commercially available electronic digital computer in the US?', 'option_a' => 'ENIAC', 'option_b' => 'UNIVAC I', 'option_c' => 'EDVAC', 'option_d' => 'MARK I', 'correct_option' => 'b', 'explanation' => 'UNIVAC I was the first commercial computer, delivered in 1951.'],
                    ['question' => 'What was the primary feature of the fourth generation of computers?', 'option_a' => 'Machine Language', 'option_b' => 'VLSI Technology', 'option_c' => 'Vacuum Tubes', 'option_d' => 'Artificial Intelligence', 'correct_option' => 'b', 'explanation' => 'The fourth generation is defined by VLSI (Very Large Scale Integration) technology.'],
                ],
                'software-programming-applications' => [
                    ['question' => 'Which programming language is known as the mother of all languages?', 'option_a' => 'Python', 'option_b' => 'C', 'option_c' => 'Java', 'option_d' => 'FORTRAN', 'correct_option' => 'b', 'explanation' => 'C language is considered the mother of all modern programming languages.'],
                    ['question' => 'What does HTML stand for?', 'option_a' => 'HyperText Markup Language', 'option_b' => 'HighTech Modern Language', 'option_c' => 'HyperTransfer Markup Language', 'option_d' => 'Home Tool Markup Language', 'correct_option' => 'a', 'explanation' => 'HTML stands for HyperText Markup Language.'],
                    ['question' => 'Which data structure uses LIFO principle?', 'option_a' => 'Queue', 'option_b' => 'Stack', 'option_c' => 'Array', 'option_d' => 'Tree', 'correct_option' => 'b', 'explanation' => 'Stack follows the Last-In-First-Out (LIFO) principle.'],
                    ['question' => 'What is the full form of CPU?', 'option_a' => 'Central Processing Unit', 'option_b' => 'Computer Personal Unit', 'option_c' => 'Central Program Unit', 'option_d' => 'Core Processing Unit', 'correct_option' => 'a', 'explanation' => 'CPU stands for Central Processing Unit.'],
                    ['question' => 'Which of the following is an object-oriented programming language?', 'option_a' => 'C', 'option_b' => 'Assembly', 'option_c' => 'Java', 'option_d' => 'COBOL', 'correct_option' => 'c', 'explanation' => 'Java is a popular object-oriented programming language.'],
                ],
                'networking-databases-security-emerging' => [
                    ['question' => 'What does IP stand for?', 'option_a' => 'Internet Protocol', 'option_b' => 'Internal Program', 'option_c' => 'Integrated Platform', 'option_d' => 'Information Provider', 'correct_option' => 'a', 'explanation' => 'IP stands for Internet Protocol.'],
                    ['question' => 'Which protocol is used for secure web browsing?', 'option_a' => 'HTTP', 'option_b' => 'HTTPS', 'option_c' => 'FTP', 'option_d' => 'SMTP', 'correct_option' => 'b', 'explanation' => 'HTTPS (HTTP Secure) encrypts data between browser and server.'],
                    ['question' => 'What is a firewall used for?', 'option_a' => 'Managing databases', 'option_b' => 'Network security', 'option_c' => 'Web development', 'option_d' => 'File compression', 'correct_option' => 'b', 'explanation' => 'A firewall monitors and controls incoming/outgoing network traffic.'],
                    ['question' => 'SQL is used for?', 'option_a' => 'Styling web pages', 'option_b' => 'Managing databases', 'option_c' => 'Programming games', 'option_d' => 'Designing graphics', 'correct_option' => 'b', 'explanation' => 'SQL (Structured Query Language) is used for managing relational databases.'],
                    ['question' => 'What is the cloud in computing?', 'option_a' => 'Weather prediction system', 'option_b' => 'Internet-based computing resources', 'option_c' => 'A type of operating system', 'option_d' => 'A hardware component', 'correct_option' => 'b', 'explanation' => 'Cloud computing provides on-demand computing resources over the internet.'],
                ],
            ],
            'everyday-science' => [
                'biology-human-body' => [
                    ['question' => 'What is the largest organ in the human body?', 'option_a' => 'Liver', 'option_b' => 'Brain', 'option_c' => 'Skin', 'option_d' => 'Heart', 'correct_option' => 'c', 'explanation' => 'The skin is the largest organ, covering about 1.5-2 square meters.'],
                    ['question' => 'How many bones are in the adult human body?', 'option_a' => '206', 'option_b' => '300', 'option_c' => '150', 'option_d' => '250', 'correct_option' => 'a', 'explanation' => 'An adult human has 206 bones.'],
                    ['question' => 'What is the powerhouse of the cell?', 'option_a' => 'Nucleus', 'option_b' => 'Ribosome', 'option_c' => 'Mitochondria', 'option_d' => 'Golgi body', 'correct_option' => 'c', 'explanation' => 'Mitochondria generate most of the cell\'s energy (ATP).'],
                    ['question' => 'What blood type is the universal donor?', 'option_a' => 'A+', 'option_b' => 'B-', 'option_c' => 'O-', 'option_d' => 'AB+', 'correct_option' => 'c', 'explanation' => 'O- blood can be donated to anyone.'],
                    ['question' => 'Which part of the brain controls balance?', 'option_a' => 'Cerebrum', 'option_b' => 'Cerebellum', 'option_c' => 'Medulla', 'option_d' => 'Hypothalamus', 'correct_option' => 'b', 'explanation' => 'The cerebellum coordinates voluntary movements and balance.'],
                ],
                'earth-space-environment' => [
                    ['question' => 'Which planet is known as the Red Planet?', 'option_a' => 'Venus', 'option_b' => 'Mars', 'option_c' => 'Jupiter', 'option_d' => 'Saturn', 'correct_option' => 'b', 'explanation' => 'Mars appears reddish due to iron oxide on its surface.'],
                    ['question' => 'What is the largest ocean on Earth?', 'option_a' => 'Atlantic', 'option_b' => 'Indian', 'option_c' => 'Arctic', 'option_d' => 'Pacific', 'correct_option' => 'd', 'explanation' => 'The Pacific Ocean is the largest and deepest.'],
                    ['question' => 'What causes seasons on Earth?', 'option_a' => 'Earth\'s rotation', 'option_b' => 'Earth\'s axial tilt', 'option_c' => 'Distance from the Sun', 'option_d' => 'Moon\'s gravity', 'correct_option' => 'b', 'explanation' => 'Earth\'s 23.5° axial tilt causes seasonal variations.'],
                    ['question' => 'Which gas makes up 78% of Earth\'s atmosphere?', 'option_a' => 'Oxygen', 'option_b' => 'Carbon Dioxide', 'option_c' => 'Nitrogen', 'option_d' => 'Argon', 'correct_option' => 'c', 'explanation' => 'Nitrogen composes about 78% of Earth\'s atmosphere.'],
                    ['question' => 'What is the ozone layer\'s primary function?', 'option_a' => 'Produce oxygen', 'option_b' => 'Block UV radiation', 'option_c' => 'Regulate temperature', 'option_d' => 'Create clouds', 'correct_option' => 'b', 'explanation' => 'The ozone layer absorbs harmful UV radiation from the Sun.'],
                ],
                'physics-chemistry' => [
                    ['question' => 'What is the chemical symbol for water?', 'option_a' => 'H2O', 'option_b' => 'CO2', 'option_c' => 'NaCl', 'option_d' => 'HCl', 'correct_option' => 'a', 'explanation' => 'Water consists of two hydrogen atoms and one oxygen atom.'],
                    ['question' => 'What is the SI unit of force?', 'option_a' => 'Joule', 'option_b' => 'Newton', 'option_c' => 'Watt', 'option_d' => 'Pascal', 'correct_option' => 'b', 'explanation' => 'The Newton is the SI unit of force.'],
                    ['question' => 'What is the speed of light in vacuum?', 'option_a' => '300,000 km/s', 'option_b' => '150,000 km/s', 'option_c' => '500,000 km/s', 'option_d' => '100,000 km/s', 'correct_option' => 'a', 'explanation' => 'Light travels at approximately 300,000 km/s in vacuum.'],
                    ['question' => 'Which element has the atomic number 1?', 'option_a' => 'Helium', 'option_b' => 'Hydrogen', 'option_c' => 'Lithium', 'option_d' => 'Carbon', 'correct_option' => 'b', 'explanation' => 'Hydrogen is the lightest element with atomic number 1.'],
                    ['question' => 'What is the formula for Newton\'s second law of motion?', 'option_a' => 'F = ma', 'option_b' => 'E = mc²', 'option_c' => 'PV = nRT', 'option_d' => 'v = u + at', 'correct_option' => 'a', 'explanation' => 'Newton\'s second law states Force = mass × acceleration.'],
                ],
            ],
        ];

        // Generic fallback questions for any topic
        $this->sampleQuestions['_default'] = [
            ['question' => 'Which of the following is correct about {n}?', 'option_a' => 'Option A', 'option_b' => 'Option B', 'option_c' => 'Option C', 'option_d' => 'Option D', 'correct_option' => 'a', 'explanation' => 'This is the correct explanation for question {n}.'],
        ];
    }

    private function getQuestionsForTopic(string $subjectSlug, string $topicSlug): array
    {
        return $this->sampleQuestions[$subjectSlug][$topicSlug]
            ?? $this->sampleQuestions['_default'];
    }
}
