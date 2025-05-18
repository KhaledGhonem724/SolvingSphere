<?php


namespace Database\Factories;


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Problem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Problem>
 */
class ProblemFactory extends Factory
{
    protected $model = Problem::class;

    public function definition(): array
    {
        return [
            'problem_handle'=> fake()->unique()->company(),// still
            'link'=> fake()->url(),
            'website'=> fake()->company(),
            'title'=> fake()->catchPhrase(),
            'timelimit'=> 'timelimit : 2.00 sec',
            'memorylimit'=> 'memorylimit : 256 MB',
            'statement'=> fake()->paragraph($nbSentences = 5, $variableNbSentences = true),
            'testcases'=> 'input: a, b, c ... output: x, y',
            'notes'=> fake()->paragraph($nbSentences = 2, $variableNbSentences = true),
        ];
    }
}
