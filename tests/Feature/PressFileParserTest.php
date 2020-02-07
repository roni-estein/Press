<?php

namespace roniestein\Press\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use roniestein\Press\PressFileParser;

class PressFileParserTest extends TestCase
{
    use RefreshDatabase;
    
    protected $author;
    
    public function setUp(): void
    {
        parent::setUp();
        
        // an author is required to exist in the database for every possible blog post test
        // except failure test
        
        $this->author = factory('roniestein\Press\Author')->create(['slug' => 'juan-valdez']);
        
    }
    
    /** @test */
    public function the_head_and_body_gets_split()
    {
        $pressFileParser = (new PressFileParser(__DIR__ . '/../scenerios/blog-without-tags/MarkFile1.md'));
        
        $data = $pressFileParser->getRawData();
        
        $this->assertStringContainsString('title: My Title', $data[1]);
        $this->assertStringContainsString('description: Description here', $data[1]);
        $this->assertStringContainsString('Blog post body here', $data[2]);
    }
    
    /** @test */
    public function a_string_can_also_be_used_instead()
    {
        $pressFileParser = (new PressFileParser("---\ntitle: My Title\n---\nBlog post body here"));
        
        $data = $pressFileParser->getRawData();
        
        $this->assertStringContainsString('title: My Title', $data[1]);
        $this->assertStringContainsString('Blog post body here', $data[2]);
    }
    
    /** @test */
    public function each_head_field_gets_separated()
    {
        $pressFileParser = (new PressFileParser(__DIR__ . '/../scenerios/blog-without-tags/MarkFile1.md'));
        
        $data = $pressFileParser->getData();
        
        $this->assertEquals('My Title', $data['title']);
        $this->assertEquals('Description here', $data['description']);
    }
    
    /** @test */
    public function the_body_gets_saved_and_trimmed()
    {
        $pressFileParser = (new PressFileParser(__DIR__ . '/../scenerios/blog-without-tags/MarkFile1.md'));
        
        $data = $pressFileParser->getData();
        
        $this->assertEquals("<h1>Heading</h1>\n<p>Blog post body here</p>", $data['body']);
    }
    
    /** @test */
    public function a_date_field_gets_parsed()
    {
        $pressFileParser = (new PressFileParser("---\ndate: May 14, 1988\n---\n"));
        
        $data = $pressFileParser->getData();
        
        $this->assertInstanceOf(Carbon::class, $data['date']);
        $this->assertEquals('05/14/1988', $data['date']->format('m/d/Y'));
    }
    
    /** @test */
    public function an_extra_field_gets_saved()
    {
        $pressFileParser = (new PressFileParser("---\nread-time: 5 min\n---\n"));
        
        $data = $pressFileParser->getData();
        
        $this->assertEquals(json_encode(['read-time' => '5 min']), $data['extra']);
    }
    
    /** @test */
    public function two_additional_fields_are_put_into_extra()
    {
        $pressFileParser = (new PressFileParser("---\nread-time: 5 min\nimage: some/image.jpg\n---\n"));
        
        $data = $pressFileParser->getData();
        
        $this->assertEquals(json_encode(['read-time' => '5 min', 'image' => 'some/image.jpg']), $data['extra']);
        
    }
}