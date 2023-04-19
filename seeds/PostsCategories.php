<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class PostsCategories extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {   

        $faker=Factory::create();

        $categories=[];

        for ($i=1; $i <=15; $i++)
        { 
            
            $categories[]=[

                    "name"=>$faker->sentence(),
                    "slug"=>$faker->slug(8)
            ];
        }

        $posts=[];


        for ($i=1; $i <=100; $i++)
        { 


            
            $posts[]=[

                    "name"=>$faker->sentence(),
                    "slug"=>$faker->slug(),
                    "content"=>$faker->paragraph(50),
                    "created_at"=>$faker->date("Y-m-d H:i:s"),
                    "updated_at"=>$faker->date("Y-m-d H:i:s")
            ];
        }

        $pc=[];

        for ($i=1; $i <=100; $i++)
        { 
            
            $pc[]=[

                    "posts_id"=>$faker->numberBetween(1,100),
                    "categories_id"=>$faker->numberBetween(1,15)
                    
            ];
        }


        $this->insert("categories",$categories);
        $this->insert("posts",$posts);
        $this->insert("posts_categories",$pc);
    }
}
