<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Review;
use App\Models\ReviewLike;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Demo Users
        $alex = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Alex Mercer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $sarah = User::firstOrCreate(
            ['email' => 'sarah@example.com'],
            [
                'name' => 'Sarah Jenkins',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $marcus = User::firstOrCreate(
            ['email' => 'marcus@example.com'],
            [
                'name' => 'Marcus Chen',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $elena = User::firstOrCreate(
            ['email' => 'elena@example.com'],
            [
                'name' => 'Elena Rostova',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Genres
        $genresData = [
            [
                'name' => 'Rock',
                'slug' => 'rock',
                'description' => 'From classic arena anthems and psychedelic soundscapes to modern indie alternative.',
                'color' => 'rose',
            ],
            [
                'name' => 'Hip-Hop',
                'slug' => 'hip-hop',
                'description' => 'Rhythmic beats, evocative wordplay, boom-bap classics, and trap innovations.',
                'color' => 'amber',
            ],
            [
                'name' => 'Electronic',
                'slug' => 'electronic',
                'description' => 'Synthesizers, dance rhythms, house, techno, French touch, and ambient chill.',
                'color' => 'cyan',
            ],
            [
                'name' => 'Pop',
                'slug' => 'pop',
                'description' => 'Catchy hooks, immaculate production, vocal powerhouses, and chart-topping records.',
                'color' => 'pink',
            ],
            [
                'name' => 'R&B & Soul',
                'slug' => 'r-and-b-soul',
                'description' => 'Silky vocal arrangements, deep emotional grooves, and neo-soul warmth.',
                'color' => 'purple',
            ],
            [
                'name' => 'Jazz',
                'slug' => 'jazz',
                'description' => 'Modal improvisation, virtuoso horn lines, syncopated rhythms, and timeless swing.',
                'color' => 'yellow',
            ],
            [
                'name' => 'Indie & Alternative',
                'slug' => 'indie-alternative',
                'description' => 'Dream pop, psych rock, bedroom recordings, and boundary-pushing independent releases.',
                'color' => 'emerald',
            ],
        ];

        $genres = [];
        foreach ($genresData as $data) {
            $genres[$data['slug']] = Genre::firstOrCreate(['slug' => $data['slug']], $data);
        }

        // 3. Create Albums
        $albumsData = [
            [
                'genre_id' => $genres['rock']->id,
                'user_id' => $alex->id,
                'title' => 'Abbey Road',
                'slug' => 'abbey-road',
                'artist' => 'The Beatles',
                'release_year' => 1969,
                'cover_url' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&auto=format&fit=crop&q=80',
                'description' => 'The eleventh studio album by the English rock band the Beatles. Renowned for its iconic zebra-crossing cover and the sweeping 16-minute medley spanning the entire B-side.',
                'spotify_url' => 'https://open.spotify.com/album/0ETFjACtuP2ADo6LFhL6HN',
                'apple_music_url' => 'https://music.apple.com/us/album/abbey-road-2019-mix/1474815798',
                'tracklist' => [
                    'Come Together',
                    'Something',
                    'Maxwell\'s Silver Hammer',
                    'Oh! Darling',
                    'Octopus\'s Garden',
                    'I Want You (She\'s So Heavy)',
                    'Here Comes the Sun',
                    'Because',
                    'You Never Give Me Your Money',
                    'Sun King',
                    'Mean Mr. Mustard',
                    'Polythene Pam',
                    'She Came In Through the Bathroom Window',
                    'Golden Slumbers',
                    'Carry That Weight',
                    'The End',
                    'Her Majesty',
                ],
            ],
            [
                'genre_id' => $genres['hip-hop']->id,
                'user_id' => $marcus->id,
                'title' => 'To Pimp a Butterfly',
                'slug' => 'to-pimp-a-butterfly',
                'artist' => 'Kendrick Lamar',
                'release_year' => 2015,
                'cover_url' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                'description' => 'A masterwork blending jazz, funk, soul, and spoken word into an uncompromising exploration of racial tension, institutional oppression, depression, and personal redemption.',
                'spotify_url' => 'https://open.spotify.com/album/7ycBtnsMtyVbbw3fMwR2Io',
                'apple_music_url' => 'https://music.apple.com/us/album/to-pimp-a-butterfly/1440828886',
                'tracklist' => [
                    'Wesley\'s Theory',
                    'For Free? (Interlude)',
                    'King Kunta',
                    'Institutionalized',
                    'These Walls',
                    'u',
                    'Alright',
                    'For Sale? (Interlude)',
                    'Momma',
                    'Hood Politics',
                    'How Much a Dollar Cost',
                    'Complexion (A Zulu Love)',
                    'The Blacker the Berry',
                    'You Ain\'t Gotta Lie (Momma Said)',
                    'i',
                    'Mortal Man',
                ],
            ],
            [
                'genre_id' => $genres['electronic']->id,
                'user_id' => $sarah->id,
                'title' => 'Random Access Memories',
                'slug' => 'random-access-memories',
                'artist' => 'Daft Punk',
                'release_year' => 2013,
                'cover_url' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&auto=format&fit=crop&q=80',
                'description' => 'Daft Punk\'s lush analog tribute to late 1970s and early 1980s American music, featuring Nile Rodgers, Giorgio Moroder, and Pharrell Williams. Won Grammy Album of the Year.',
                'spotify_url' => 'https://open.spotify.com/album/4m2880jivSbbyEGAKfITCa',
                'apple_music_url' => 'https://music.apple.com/us/album/random-access-memories/617154241',
                'tracklist' => [
                    'Give Life Back to Music',
                    'The Game of Love',
                    'Giorgio by Moroder',
                    'Within',
                    'Instant Crush',
                    'Lose Yourself to Dance',
                    'Touch',
                    'Get Lucky',
                    'Beyond',
                    'Motherboard',
                    'Fragments of Time',
                    'Doin\' It Right',
                    'Contact',
                ],
            ],
            [
                'genre_id' => $genres['r-and-b-soul']->id,
                'user_id' => $elena->id,
                'title' => 'Blonde',
                'slug' => 'blonde',
                'artist' => 'Frank Ocean',
                'release_year' => 2016,
                'cover_url' => 'https://images.unsplash.com/photo-1518609878373-06d740f60d8b?w=800&auto=format&fit=crop&q=80',
                'description' => 'An ethereal, minimalistic, and profoundly vulnerable avant-garde R&B record that defined a generation. Stripped back percussion highlights Frank\'s unmatched storytelling.',
                'spotify_url' => 'https://open.spotify.com/album/3mH6qwIy9crq0Oi9Y9r2mu',
                'apple_music_url' => 'https://music.apple.com/us/album/blonde/1146195596',
                'tracklist' => [
                    'Nikes',
                    'Ivy',
                    'Pink + White',
                    'Be Yourself',
                    'Solo',
                    'Skyline To',
                    'Self Control',
                    'Good Guy',
                    'Nights',
                    'Solo (Reprise)',
                    'Pretty Sweet',
                    'Facebook Story',
                    'Close to You',
                    'White Ferrari',
                    'Seigfried',
                    'Godspeed',
                    'Futura Free',
                ],
            ],
            [
                'genre_id' => $genres['indie-alternative']->id,
                'user_id' => $alex->id,
                'title' => 'Currents',
                'slug' => 'currents',
                'artist' => 'Tame Impala',
                'release_year' => 2015,
                'cover_url' => 'https://images.unsplash.com/photo-1498038432885-c6f3f1b912ee?w=800&auto=format&fit=crop&q=80',
                'description' => 'Kevin Parker pivots from psychedelic garage rock to shimmering synth-pop and vintage dance textures, creating an emotional tour de force about personal evolution.',
                'spotify_url' => 'https://open.spotify.com/album/79dL7FLiJFOO0Epm8UCRb0',
                'apple_music_url' => 'https://music.apple.com/us/album/currents/1440838039',
                'tracklist' => [
                    'Let It Happen',
                    'Nangs',
                    'The Moment',
                    'Yes I\'m Changing',
                    'Eventually',
                    'Gossip',
                    'The Less I Know the Better',
                    'Past Life',
                    'Disciples',
                    'Cause I\'m a Man',
                    'Reality in Motion',
                    'Love/Paranoia',
                    'New Person, Same Old Mistakes',
                ],
            ],
            [
                'genre_id' => $genres['rock']->id,
                'user_id' => $sarah->id,
                'title' => 'Rumours',
                'slug' => 'rumours',
                'artist' => 'Fleetwood Mac',
                'release_year' => 1977,
                'cover_url' => 'https://images.unsplash.com/photo-1511735111819-9a3f7709049c?w=800&auto=format&fit=crop&q=80',
                'description' => 'Recorded amidst the catastrophic romantic breakdowns of all five band members, Rumours transformed heartbreak and interpersonal fury into immaculate pop-rock gold.',
                'spotify_url' => 'https://open.spotify.com/album/1B26kQ3LYKyS9bhoQVDCdV',
                'apple_music_url' => 'https://music.apple.com/us/album/rumours-super-deluxe-edition/598232671',
                'tracklist' => [
                    'Second Hand News',
                    'Dreams',
                    'Never Going Back Again',
                    'Don\'t Stop',
                    'Go Your Own Way',
                    'Songbird',
                    'The Chain',
                    'You Make Loving Fun',
                    'I Don\'t Want to Know',
                    'Oh Daddy',
                    'Gold Dust Woman',
                ],
            ],
            [
                'genre_id' => $genres['jazz']->id,
                'user_id' => $marcus->id,
                'title' => 'Kind of Blue',
                'slug' => 'kind-of-blue',
                'artist' => 'Miles Davis',
                'release_year' => 1959,
                'cover_url' => 'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?w=800&auto=format&fit=crop&q=80',
                'description' => 'Regarded as the pinnacle of modal jazz and the greatest jazz recording ever made, featuring John Coltrane, Cannonball Adderley, Bill Evans, Paul Chambers, and Jimmy Cobb.',
                'spotify_url' => 'https://open.spotify.com/album/1weenldGlxnxIRagDFQUZe',
                'apple_music_url' => 'https://music.apple.com/us/album/kind-of-blue-legacy-edition/268443092',
                'tracklist' => [
                    'So What',
                    'Freddie Freeloader',
                    'Blue in Green',
                    'All Blues',
                    'Flamenco Sketches',
                ],
            ],
            [
                'genre_id' => $genres['pop']->id,
                'user_id' => $elena->id,
                'title' => 'Future Nostalgia',
                'slug' => 'future-nostalgia',
                'artist' => 'Dua Lipa',
                'release_year' => 2020,
                'cover_url' => 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=800&auto=format&fit=crop&q=80',
                'description' => 'A sparkling dance-pop and nu-disco juggernaut loaded with bass-heavy hooks, infectious rhythm sections, and timeless disco escapism.',
                'spotify_url' => 'https://open.spotify.com/album/04m16NXj4K5wY0fEwXq2F5',
                'apple_music_url' => 'https://music.apple.com/us/album/future-nostalgia/1538003494',
                'tracklist' => [
                    'Future Nostalgia',
                    'Don\'t Start Now',
                    'Cool',
                    'Physical',
                    'Levitating',
                    'Pretty Please',
                    'Hallucinate',
                    'Love Again',
                    'Break My Heart',
                    'Good in Bed',
                    'Boys Will Be Boys',
                ],
            ],
        ];

        $albums = [];
        foreach ($albumsData as $data) {
            $albums[$data['slug']] = Album::firstOrCreate(['slug' => $data['slug']], $data);
        }

        // 4. Create Sample Reviews
        $reviewsData = [
            // Abbey Road Reviews
            [
                'user_id' => $sarah->id,
                'album_id' => $albums['abbey-road']->id,
                'rating' => 5,
                'title' => 'The greatest B-side medley in music history',
                'content' => 'Abbey Road is perfection from start to finish. George Harrison delivers two of his finest compositions with "Something" and "Here Comes the Sun", while the concluding medley remains unmatched in rock.',
                'favorite_track' => 'Here Comes the Sun',
                'is_recommended' => true,
                'likes_count' => 14,
            ],
            [
                'user_id' => $marcus->id,
                'album_id' => $albums['abbey-road']->id,
                'rating' => 5,
                'title' => 'A magnificent final recording from the Fab Four',
                'content' => 'Even as tensions simmered, the chemistry between the four musicians shines through in every note. Paul McCartney\'s basslines across this entire record are legendary.',
                'favorite_track' => 'Come Together',
                'is_recommended' => true,
                'likes_count' => 8,
            ],

            // To Pimp a Butterfly Reviews
            [
                'user_id' => $alex->id,
                'album_id' => $albums['to-pimp-a-butterfly']->id,
                'rating' => 5,
                'title' => 'A generational cultural and musical landmark',
                'content' => 'TPAB is more than hip-hop; it is modern literature set to Thundercat\'s bass and Kamasi Washington\'s saxophone. "How Much a Dollar Cost" and "Alright" will echo for decades.',
                'favorite_track' => 'Alright',
                'is_recommended' => true,
                'likes_count' => 22,
            ],
            [
                'user_id' => $elena->id,
                'album_id' => $albums['to-pimp-a-butterfly']->id,
                'rating' => 5,
                'title' => 'Dense, poignant, and endlessly rewarding',
                'content' => 'Every single listen reveals another layer of lyricism or subtle instrumentation. The transition from the breakdown on "u" to the triumphant energy of "Alright" is chill-inducing.',
                'favorite_track' => 'The Blacker the Berry',
                'is_recommended' => true,
                'likes_count' => 12,
            ],

            // Random Access Memories Reviews
            [
                'user_id' => $alex->id,
                'album_id' => $albums['random-access-memories']->id,
                'rating' => 5,
                'title' => 'The warmest analog production of the 21st century',
                'content' => 'Instead of relying on digital loops, the French robots hired studio virtuosos and recorded to analog tape. "Giorgio by Moroder" and "Touch" are cinematic milestones.',
                'favorite_track' => 'Instant Crush',
                'is_recommended' => true,
                'likes_count' => 19,
            ],
            [
                'user_id' => $sarah->id,
                'album_id' => $albums['random-access-memories']->id,
                'rating' => 4,
                'title' => 'Exquisite sound engineering with disco soul',
                'content' => 'An immaculate auditory experience. Put on a good pair of headphones and listen to Nile Rodgers\' rhythm guitar on "Lose Yourself to Dance". Sensational.',
                'favorite_track' => 'Get Lucky',
                'is_recommended' => true,
                'likes_count' => 6,
            ],

            // Blonde Reviews
            [
                'user_id' => $sarah->id,
                'album_id' => $albums['blonde']->id,
                'rating' => 5,
                'title' => 'An intimate sanctuary in album form',
                'content' => 'Blonde feels like reading somebody\'s private diary at 3am. The beat switch in "Nights" literally marks the exact mathematical midpoint of the album. Pure genius.',
                'favorite_track' => 'Nights',
                'is_recommended' => true,
                'likes_count' => 25,
            ],
            [
                'user_id' => $marcus->id,
                'album_id' => $albums['blonde']->id,
                'rating' => 5,
                'title' => 'Vulnerable and hauntingly beautiful',
                'content' => 'The vocal pitch shifts, acoustic guitars, and atmospheric reverbs make this record feel timeless. "White Ferrari" leaves me speechless every single time.',
                'favorite_track' => 'White Ferrari',
                'is_recommended' => true,
                'likes_count' => 11,
            ],

            // Currents Reviews
            [
                'user_id' => $marcus->id,
                'album_id' => $albums['currents']->id,
                'rating' => 4,
                'title' => 'Psych-rock meets synth-pop perfection',
                'content' => '"Let It Happen" has one of the best faux-CD-skipping drops in music history. A brilliant record for late night drives and introspection.',
                'favorite_track' => 'The Less I Know the Better',
                'is_recommended' => true,
                'likes_count' => 9,
            ],

            // Rumours Reviews
            [
                'user_id' => $alex->id,
                'album_id' => $albums['rumours']->id,
                'rating' => 5,
                'title' => 'Turmoil turned into absolute magic',
                'content' => 'When John McVie and Lindsey Buckingham kick in during the climax of "The Chain", it is pure rock and roll bliss. Not a single weak track on the entire tracklist.',
                'favorite_track' => 'The Chain',
                'is_recommended' => true,
                'likes_count' => 16,
            ],

            // Kind of Blue Reviews
            [
                'user_id' => $elena->id,
                'album_id' => $albums['kind-of-blue']->id,
                'rating' => 5,
                'title' => 'Cool jazz at its highest artistic expression',
                'content' => 'The moment Bill Evans plays the opening chords of "Blue in Green", time stops. The quintet was improvising over modal sketches with hardly any rehearsal, capturing raw lightning.',
                'favorite_track' => 'So What',
                'is_recommended' => true,
                'likes_count' => 13,
            ],

            // Future Nostalgia Reviews
            [
                'user_id' => $alex->id,
                'album_id' => $albums['future-nostalgia']->id,
                'rating' => 4,
                'title' => 'Pure unadulterated disco-pop euphoria',
                'content' => 'In a difficult year, Future Nostalgia was an infectious celebration. The baseline in "Don\'t Start Now" is irresistible and Dua\'s vocals are commanding.',
                'favorite_track' => 'Levitating',
                'is_recommended' => true,
                'likes_count' => 7,
            ],
        ];

        foreach ($reviewsData as $rev) {
            $createdReview = Review::firstOrCreate(
                ['user_id' => $rev['user_id'], 'album_id' => $rev['album_id']],
                $rev
            );

            // Seed likes for reviews
            ReviewLike::firstOrCreate([
                'user_id' => $alex->id,
                'review_id' => $createdReview->id,
            ]);
        }
    }
}
