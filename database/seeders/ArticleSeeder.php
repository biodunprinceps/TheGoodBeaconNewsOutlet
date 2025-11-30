<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::first();
    if (!$user) {
      $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@goodbeacon.com',
        'password' => bcrypt('admin123'),
      ]);
    }

    $categories = Category::all();
    $tags = Tag::all();

    $articles = [
      [
        'title' => 'Breakthrough in Quantum Computing: Scientists Achieve New Milestone',
        'excerpt' => 'Researchers at leading universities have made a significant breakthrough in quantum computing, bringing us closer to practical quantum computers.',
        'content' => '<h2>A New Era in Computing</h2><p>In a groundbreaking development, scientists at the Institute of Quantum Technology have successfully demonstrated a quantum computer capable of solving complex problems that would take classical computers thousands of years to complete.</p><p>The team, led by Dr. Sarah Chen, utilized a novel approach combining superconducting qubits with advanced error correction techniques. This achievement marks a significant milestone in the field of quantum computing.</p><blockquote>"This is a game-changer for the field. We\'ve overcome one of the biggest challenges in quantum computing - maintaining quantum coherence for extended periods," said Dr. Chen.</blockquote><h3>What This Means for the Future</h3><p>The implications of this breakthrough are far-reaching:</p><ul><li>Drug discovery and molecular modeling</li><li>Cryptography and secure communications</li><li>Climate modeling and weather prediction</li><li>Artificial intelligence and machine learning</li></ul><p>Industry experts predict that this development could accelerate the timeline for commercially viable quantum computers by several years.</p>',
        'category' => 'Technology',
        'tags' => ['Breaking News', 'Innovation', 'Featured'],
        'status' => 'published',
        'is_featured' => true,
        'views' => rand(1000, 5000),
      ],
      [
        'title' => 'Global Climate Summit: World Leaders Commit to Carbon Neutrality by 2050',
        'excerpt' => 'Over 150 nations have pledged to achieve net-zero emissions by 2050 in the most significant climate agreement since Paris.',
        'content' => '<h2>Historic Agreement Reached</h2><p>World leaders gathered in Geneva this week for the Global Climate Summit, resulting in the most comprehensive climate agreement in history. The summit concluded with 157 nations committing to achieve carbon neutrality by 2050.</p><p>The agreement includes concrete measures for renewable energy adoption, forest conservation, and sustainable development goals.</p><h3>Key Provisions</h3><p>The agreement outlines several critical action points:</p><ol><li>Phase out coal-fired power plants by 2035</li><li>Increase renewable energy capacity by 300%</li><li>Invest $2 trillion in green technology</li><li>Establish global carbon pricing mechanism</li></ol><p>"This is humanity\'s moment. We must act now to secure a sustainable future for generations to come," stated UN Secretary-General Maria Santos.</p>',
        'category' => 'World News',
        'tags' => ['Climate Change', 'International', 'Breaking News'],
        'status' => 'published',
        'is_featured' => true,
        'views' => rand(2000, 6000),
      ],
      [
        'title' => 'Stock Markets Rally as Tech Giants Report Record Earnings',
        'excerpt' => 'Major technology companies exceeded expectations in Q4, driving stock markets to new heights.',
        'content' => '<h2>Tech Sector Leads Market Surge</h2><p>Global stock markets experienced their best week in months as major technology companies reported earnings that far exceeded analyst expectations. The S&P 500 and NASDAQ both reached record highs.</p><p>Leading the charge were companies in artificial intelligence, cloud computing, and semiconductor manufacturing.</p><h3>Standout Performers</h3><p>Several companies posted remarkable results:</p><ul><li>AI Corp: Revenue up 45% year-over-year</li><li>Cloud Systems: User growth exceeded 50 million</li><li>Chip Manufacturers: Record production capacity</li></ul><blockquote>"The digital transformation accelerated by recent global events has created unprecedented opportunities for tech companies," noted financial analyst Robert Martinez.</blockquote><p>Investors remain optimistic about continued growth in the technology sector, particularly in emerging fields like artificial intelligence and renewable energy technology.</p>',
        'category' => 'Business',
        'tags' => ['Economy', 'Trending', 'Analysis'],
        'status' => 'published',
        'is_featured' => false,
        'views' => rand(500, 2000),
      ],
      [
        'title' => 'New Study Reveals Benefits of Mediterranean Diet for Brain Health',
        'excerpt' => 'Research shows that following a Mediterranean diet can significantly reduce the risk of cognitive decline and dementia.',
        'content' => '<h2>Diet and Brain Health Connection</h2><p>A comprehensive 10-year study involving over 20,000 participants has demonstrated that adherence to a Mediterranean diet is associated with a 35% reduction in the risk of cognitive decline.</p><p>The research, published in the Journal of Neuroscience, examined the long-term effects of dietary patterns on brain health.</p><h3>Key Findings</h3><p>The study revealed several important insights:</p><ul><li>Regular consumption of olive oil improved memory function</li><li>Fish intake was linked to better cognitive performance</li><li>Nuts and seeds showed protective effects against neurodegeneration</li><li>Moderate wine consumption was associated with reduced dementia risk</li></ul><p>Lead researcher Dr. Elena Rossi emphasized that it\'s never too late to adopt healthier eating habits. "Our data shows that even participants who switched to a Mediterranean diet later in life experienced cognitive benefits."</p>',
        'category' => 'Health',
        'tags' => ['Featured', 'Analysis', 'Investigation'],
        'status' => 'published',
        'is_featured' => false,
        'views' => rand(800, 2500),
      ],
      [
        'title' => 'SpaceX Successfully Launches First Commercial Space Station Module',
        'excerpt' => 'Private space company achieves milestone with successful deployment of commercial orbital habitat.',
        'content' => '<h2>Private Space Industry Milestone</h2><p>SpaceX has successfully launched and deployed the first module of what will become the world\'s first fully commercial space station. The launch represents a significant milestone in the commercialization of space.</p><p>The module, named "Genesis 1," will serve as the foundation for an orbital research and tourism facility expected to be fully operational by 2027.</p><h3>Future of Space Commerce</h3><p>This achievement opens new possibilities:</p><ol><li>Commercial research laboratories in microgravity</li><li>Space tourism opportunities for civilians</li><li>Manufacturing in zero-gravity environments</li><li>Testing ground for deep space technologies</li></ol><blockquote>"Today marks the beginning of a new chapter in human space exploration. The commercial space station will democratize access to space," said SpaceX CEO.</blockquote>',
        'category' => 'Science',
        'tags' => ['Space Exploration', 'Innovation', 'Breaking News'],
        'status' => 'published',
        'is_featured' => true,
        'views' => rand(3000, 7000),
      ],
      [
        'title' => 'Major Film Festival Announces Lineup: Record Number of Diverse Voices',
        'excerpt' => 'This year\'s festival features unprecedented representation from filmmakers around the globe.',
        'content' => '<h2>Celebrating Global Cinema</h2><p>The Annual International Film Festival has announced its most diverse lineup in the event\'s 75-year history, featuring films from 82 countries and representing voices from every continent.</p><p>Festival director Amanda Rodriguez highlighted the commitment to showcasing stories that reflect the global human experience.</p><h3>Festival Highlights</h3><p>Notable selections include:</p><ul><li>Opening night feature from acclaimed director Yuki Tanaka</li><li>Documentary series on climate activism</li><li>Retrospective of African cinema pioneers</li><li>Virtual reality storytelling experiences</li></ul><p>"Film has the power to build bridges between cultures. This year\'s selections demonstrate the universal language of storytelling," Rodriguez stated.</p>',
        'category' => 'Entertainment',
        'tags' => ['Featured', 'International', 'Exclusive'],
        'status' => 'published',
        'is_featured' => false,
        'views' => rand(600, 1800),
      ],
      [
        'title' => 'Championship Final: Underdog Team Claims Historic Victory',
        'excerpt' => 'Against all odds, the lowest-seeded team wins the championship in a thrilling overtime finish.',
        'content' => '<h2>Historic Upset Victory</h2><p>In one of the most dramatic championship finals in sports history, the sixth-seeded United defeated the defending champions 3-2 in overtime, claiming their first title in franchise history.</p><p>The victory caps off an incredible playoff run that saw the underdogs eliminate three higher-seeded opponents.</p><h3>Game Highlights</h3><p>The final was filled with memorable moments:</p><ul><li>Goalkeeper made 47 saves, including penalty stop</li><li>Rookie scored game-tying goal with 2 minutes remaining</li><li>Captain netted overtime winner in front of home crowd</li><li>Team overcame 2-0 deficit in the third period</li></ul><blockquote>"This is for our fans who believed in us when no one else did. This is for our city," said team captain Marcus Johnson.</blockquote>',
        'category' => 'Sports',
        'tags' => ['Breaking News', 'Trending', 'Featured'],
        'status' => 'published',
        'is_featured' => false,
        'views' => rand(1500, 4000),
      ],
      [
        'title' => 'New AI Tool Helps Detect Early Signs of Rare Diseases',
        'excerpt' => 'Machine learning algorithm shows 95% accuracy in identifying rare genetic conditions from routine blood tests.',
        'content' => '<h2>AI in Medical Diagnosis</h2><p>Researchers have developed an artificial intelligence system capable of detecting early signs of rare genetic diseases with unprecedented accuracy. The tool analyzes standard blood test results using advanced machine learning algorithms.</p><p>The breakthrough could revolutionize early diagnosis and treatment of conditions that often go undetected until symptoms become severe.</p><h3>How It Works</h3><p>The AI system:</p><ol><li>Analyzes patterns in routine blood work</li><li>Compares results against database of rare conditions</li><li>Identifies subtle markers invisible to human analysis</li><li>Flags potential cases for specialist review</li></ol><p>Clinical trials involving 10,000 patients showed the system correctly identified 95% of rare disease cases that were later confirmed by specialists.</p><blockquote>"This technology could save countless lives by enabling intervention before irreversible damage occurs," explained lead developer Dr. James Park.</blockquote>',
        'category' => 'Technology',
        'tags' => ['AI & Machine Learning', 'Innovation', 'Featured'],
        'status' => 'published',
        'is_featured' => false,
        'views' => rand(1200, 3500),
      ],
      [
        'title' => 'Election Results: New Government Promises Economic Reform',
        'excerpt' => 'Opposition party wins decisive victory on platform of economic renewal and social programs.',
        'content' => '<h2>Change in Leadership</h2><p>In a historic electoral shift, the opposition Progressive Party has won a decisive majority, ending the incumbent government\'s 12-year tenure. The victory was built on promises of economic reform and expanded social programs.</p><p>President-elect Alexandra Morrison pledged to unite the nation and deliver on campaign promises within the first 100 days.</p><h3>Policy Priorities</h3><p>The new government\'s agenda includes:</p><ul><li>Universal healthcare expansion</li><li>Green energy investment program</li><li>Education reform and student debt relief</li><li>Infrastructure modernization</li></ul><p>Political analysts attribute the victory to strong support from younger voters and urban constituencies. "This election represents a generational shift in priorities," noted political scientist Dr. Thomas Wright.</p>',
        'category' => 'Politics',
        'tags' => ['Elections', 'Breaking News', 'Analysis'],
        'status' => 'published',
        'is_featured' => false,
        'views' => rand(2000, 5000),
      ],
      [
        'title' => 'Cybersecurity Alert: Major Vulnerabilities Discovered in Popular Software',
        'excerpt' => 'Security researchers identify critical flaws affecting millions of users worldwide.',
        'content' => '<h2>Critical Security Update Required</h2><p>Cybersecurity researchers have discovered multiple critical vulnerabilities in widely-used business software that could allow attackers to gain unauthorized access to sensitive data.</p><p>Software vendors have released emergency patches and urge all users to update immediately.</p><h3>What Users Should Do</h3><p>Security experts recommend:</p><ol><li>Install all available security updates immediately</li><li>Change passwords for affected accounts</li><li>Enable two-factor authentication</li><li>Monitor accounts for suspicious activity</li></ol><blockquote>"The severity of these vulnerabilities cannot be overstated. Organizations must act immediately to protect their systems," warned cybersecurity expert Lisa Chang.</blockquote><p>No evidence of active exploitation has been detected, but researchers emphasize the urgency of applying patches before malicious actors develop working exploits.</p>',
        'category' => 'Technology',
        'tags' => ['Cybersecurity', 'Breaking News', 'Investigation'],
        'status' => 'published',
        'is_featured' => false,
        'views' => rand(800, 2200),
      ],
    ];

    foreach ($articles as $articleData) {
      $category = $categories->firstWhere('name', $articleData['category']);
      $articleTags = $tags->whereIn('name', $articleData['tags']);

      $article = Article::create([
        'title' => $articleData['title'],
        'slug' => Str::slug($articleData['title']),
        'excerpt' => $articleData['excerpt'],
        'content' => $articleData['content'],
        'user_id' => $user->id,
        'category_id' => $category->id,
        'meta_title' => Str::limit($articleData['title'], 60),
        'meta_description' => Str::limit($articleData['excerpt'], 160),
        'status' => $articleData['status'],
        'is_featured' => $articleData['is_featured'],
        'views' => $articleData['views'],
        'published_at' => now()->subDays(rand(0, 30)),
      ]);

      // Attach tags
      $article->tags()->attach($articleTags->pluck('id'));
    }

    $this->command->info('✅ Created ' . count($articles) . ' sample articles');
  }
}
