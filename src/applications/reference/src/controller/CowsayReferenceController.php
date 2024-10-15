<?php

final class CowsayReferenceController extends ReferenceController {
  private function getJokes() {
    $jokes = array();
    $jokes[] =
      $jokes[] = 'Why did the team leader cross the road? To get to the'.
      ' other side and avoid responsibility.';
    $jokes[] = "What do you call a mathematician who's afraid of".
      ' negative numbers? A coward.';
    $jokes[] = 'Why did the sales person cross the road? To sell the'.
      ' chicken a new coop.';
    $jokes[] = 'I told my doctor that I broke my arm in two places. He'.
      ' told me to stop going to those places.';
    $jokes[] = 'Why do managers always carry a briefcase? So they have'.
      " something heavy to throw at their employees when they're not".
      ' meeting their targets.';
    $jokes[] = 'What do you call a sales person with a conscience?'.
      ' Unemployed.';
    $jokes[] = 'Why did the biologist bring a microscope to the party?'.
      ' Because she wanted to see things up close and personal.';
    $jokes[] = "Why do IT guys like to work in the dark? Because it's".
      ' easier to hide their mistakes';
    $jokes[] = 'How do you recognize a happy motorcyclist? By the bugs'.
      ' in his teeth.';
    $jokes[] = 'Why did the tomato turn red? Because it saw the salad'.
      ' dressing.';
    $jokes[] = 'Why did the engineer go to the beach? To build sand'.
      ' castles, of course.';
    $jokes[] = "Why don't sales reps use trampolines? They never bounce".
      ' back from rejection.';
    $jokes[] = "Why don't sales people play hide and seek? Because".
      ' nobody would look for them.';
    $jokes[] = 'Why did the lumberjack break up with his girlfriend? She'.
      " couldn't handle his wood.";
    $jokes[] = 'I once went to a meeting that was so pointless, I'.
      ' started questioning the meaning of life...';
    $jokes[] = 'How many sales people does it take to change a'.
      " lightbulb? None, they'll convince you the old one is still working".
      ' fine.';
    $jokes[] = "What do you call an AI that can't learn from its".
      ' mistakes? A broken record.';
    $jokes[] = 'Why did the engineer bring a ruler to bed? He wanted to'.
      ' see how long he could sleep.';
    $jokes[] = "What did the sales rep say to the customer who couldn't".
      " afford the product? Don't worry, it's a steal!";
    $jokes[] = "I just burned 2000 calories. That's the last time I".
      ' leave brownies in the oven while I nap.';
    $jokes[] = 'Why was 6 afraid of 7? Because 7 8 9.';
    $jokes[] = 'Why did the coffee file a police report? It got mugged.';
    $jokes[] = 'Why did the plumber go to the opera? He wanted to see a'.
      ' show about pipe organs.';
    $jokes[] = 'How many HR people does it take to change a light bulb?'.
      " None, they're too busy writing a policy on it.";
    $jokes[] = "What did the coffee say to the tea? You're too weak".
      ' for my taste.';
    $jokes[] = 'Programmers are a unique breed of individuals who have'.
      ' the power to turn coffee into code.';
    $jokes[] = 'What do you call an opera singer who can also fix a'.
      ' car? A mechanic.';
    $jokes[] = "Dog owners are really the only people who think it's".
      " normal to pick up another species' poop.";
    $jokes[] = 'Why do some people bring a notebook to meetings? So'.
      " they can write down all the reasons they're never going to do any".
      ' of the things discussed in the meeting.';
    $jokes[] = 'Why did the project manager go to the beach? To check'.
      ' if the tide was on schedule.';
    $jokes[] = 'How many Nobel laureates does it take to change a'.
      ' lightbulb? Two. One to hold the lightbulb and the other to'.
      ' rotate the universe.';
    $jokes[] = 'Why did the chemist bring a calculator to the party?'.
      ' Because she wanted to find the solution to every problem.';
    $jokes[] = 'Why did the cyclist bring a ladder to the race? He'.
      ' wanted to get to the top of the podium.';
    $jokes[] = "Why don't eggs tell jokes? They'd crack each other up.";
    $jokes[] = 'Why was the math book sad? It had too many problems.';
    $jokes[] = "Trust me, this is a once-in-a-lifetime opportunity.
       It's like a unicorn, but with more broken promises.";
    $jokes[] = 'Why do programmers prefer dark mode? Because'.
      ' light attracts bugs.';
    $jokes[] = "How many women does it take to change a lightbulb?'.
      ' None, they just sit in the dark and bitch about how they".
      " can't see anything!";
    $jokes[] = "You won't find a better deal anywhere else, unless,".
      ' of course, you look on the internet.';
    $jokes[] = 'How do you know if a programmer is extroverted?'.
      ' They stare at your shoes when they talk to you.';
    $jokes[] = "\"I'm sorry\" and \"I apologize\" mean the same".
      " thing. Unless you're at a funeral.";
    $jokes[] = 'A man walks into a bar...ouch';
    $jokes[] = 'Why do managers always have their hands in their'.
      " pockets? Because they're trying to find the key to their".
      " employees' motivation.";
    $jokes[] = "What do you call a brat who doesn't believe in".
      ' Santa? A rebel without a Claus.';
    $jokes[] = 'How many programmers does it take to change a light'.
      " bulb? None, that's a hardware issue.";
    $jokes[] = 'How does NASA organize a party? They planet.';
    $jokes[] = "I'm reading a book on anti-gravity. It's impossible".
      ' to put down.';
    $jokes[] = "Why don't mathematicians get married? They'd always".
      ' be looking for a better half.';
    $jokes[] = "What's an electrical engineer's favorite type of".
      ' exercise? Circuit training.';
    $jokes[] = 'How does a CEO prepare for a presentation? By'.
      ' practicing his power-point-of-view.';
    $jokes[] = 'Why did the salesman bring a ladder to the sales'.
      ' meeting? To reach his quota.';
    $jokes[] = 'The special offer is like a regular offer, but'.
      ' with more glitter and less substance.';
    $jokes[] = 'How does a project manager propose to his partner?'.
      ' \"Will you accept this project to marry me?\"';
    $jokes[] = "Why did the programmer quit his job? He didn't".
      ' get arrays.';
    $jokes[] = 'Why did the chemist take a piece of chalk to bed?'.
      ' To draw a conclusion.';
    $jokes[] = "What's the difference between a sales person and a".
      ' mosquito? A mosquito stops sucking when you slap it.';
    $jokes[] = "Why don't scientists trust atoms? Because they".
      ' make up everything.';
    $jokes[] = 'Why do women have small feet? So they can stand'.
      ' closer to the kitchen sink.';
    $jokes[] = 'I just ordered a chicken and an egg from Amazon.'.
      " I'll let you know.";
    $jokes[] = "Why don't help desk technicians go on vacation?".
      " Because they're afraid the users will break something while".
      " they're gone.";
    $jokes[] = "I'm on a seafood diet. I see food, I eat it.";
    $jokes[] = "What's the difference between a motorcycle and a".
      ' vacuum cleaner? The location of the dirtbag.';
    $jokes[] = 'Why did the two 4s skip lunch? They already 8.';
    $jokes[] = "What do you call a programmer who doesn't comment".
      ' their code? A future team leader.';
    $jokes[] = 'Why did the cookie go to the doctor? Because he was'.
      ' feeling crumby.';
    $jokes[] = "What's the difference between a poorly dressed man on".
      ' a unicycle and a well-dressed man on a bicycle? Attire.';
    $jokes[] = 'Why did the statistician always carry a ruler to the'.
      ' party? He wanted to measure the standard deviation of the fun.';
    $jokes[] = 'Why did the math teacher go to the beach? He wanted'.
      ' to work on his tan-gent.';
    $jokes[] = 'What do you call a factory that makes okay products?'.
      ' A satisfactory.';
    $jokes[] = 'Why did the philosopher refuse to buy a new calendar?'.
      ' He wanted to wait until the end of time.';
    $jokes[] = 'Why do civil engineers refuse to play hide and seek?'.
      ' They always leave concrete evidence.';
    $jokes[] = 'I told the help desk technician that my computer was'.
      " running slow, and he said, \"That's because it's trying to keep".
      ' up with your brain.\"';
    $jokes[] = "What's the difference between a sales person and a".
      ' magician? A magician knows when to disappear.';
    $jokes[] = 'Why did the sales rep sit on the photocopier? He wanted'.
      ' to make a good impression.';
    $jokes[] = 'Why do scuba divers fall backwards off the boat?'.
      ' Because if they fell forward, they would still be in the boat.';
    $jokes[] = "Why do some people love meetings? Because it's the".
      ' only time they can catch up on their sleep without getting fired.';
    $jokes[] = 'What do you call fake spaghetti? An impasta.';
    $jokes[] = 'Why did the electrical engineer become a comedian?'.
      ' They wanted to make light of the situation.';
    $jokes[] = "Why do men like to play video games? Because it's the".
      ' only way they can win.';
    $jokes[] = 'Why did the graduate cross the road? To prove to their'.
      " parents that they could make it on their own... even if it's just".
      ' to the other side of the street.';
    $jokes[] = 'Why did the manufacturing operator get fired? He'.
      " couldn't even screw up the right way.";
    $jokes[] = "Why don't politicians campaign in cemeteries? Because".
      " they're afraid of waking the voters.";
    $jokes[] = 'What do you call a programmer who never makes'.
      ' mistakes? A liar.';
    $jokes[] = 'Why did the manager go to the psychologist? Because'.
      ' he wanted to learn how to delegate his problems to someone else.';
    $jokes[] = 'I sent my wife to the kitchen to make me a sandwich,'.
      " but she couldn't find it.";
    $jokes[] = "What rock group has four men who don't sing? Mount".
      ' Rushmore.';
    $jokes[] = 'Why did the motorcycle go to the doctor? It was two'.
      ' tired.';
    $jokes[] = "Why don't project managers make good cooks? They".
      ' always follow the recipe too closely and never improvise.';
    $jokes[] = 'I told my wife she was drawing her eyebrows too high.'.
      ' She looked surprised.';
    $jokes[] = 'If you think hunting is expensive, think of all the'.
      " money you'll save when your friends stop inviting you out to".
      ' dinner.';
    $jokes[] = 'Why do meetings always start late? Because the boss'.
      " is too busy practicing his \"I'm important\" face in the mirror.";
    $jokes[] = "I've heard that smoking is a dying habit, but I".
      " didn't realize it was so literal.";
    $jokes[] = "I asked God for a bike, but I know God doesn't work".
      ' that way. So I stole a bike and asked for forgiveness.';
    $jokes[] = "What do you call a project manager who doesn't believe".
      ' in deadlines? A myth.';
    $jokes[] = 'Why do meetings feel like they last forever? Because'.
      " time flies when you're having fun.";
    $jokes[] = 'Why did the HR person become a detective? They'.
      ' wanted to solve the mystery of the missing stapler.';
    $jokes[] = 'The IT guy at work seemingly vanished today.'.
      ' He ransomware.';
    $jokes[] = 'My imaginary friend thinks I have problems.';
    $jokes[] = "Why don't climate activists go on vacation? Because".
      " they're afraid the ice caps will melt while they're gone.";
    $jokes[] = 'Why do sales people wear slip-on shoes? You'.
      " don't need to tie something up if you're going to run away with it.";

    return $jokes;
  }

  public function getTitle() {
    return 'Cowsay reference';
  }

  public function getContent() {
    $content = <<<EOTEXT
= Cowsay reference
== Templates
EOTEXT;

    $root = dirname(phutil_get_library_root('phorge'));

    $directories = array(
      $root.'/externals/cowsay/cows/',
      $root.'/resources/cows/builtin/',
      $root.'/resources/cows/custom/',
    );

    $jokes = $this->getJokes();
    shuffle($jokes);

    $j = 0;

    foreach ($directories as $directory) {
      foreach (Filesystem::listDirectory($directory, false) as $cow_file) {
        $matches = null;
        if (!preg_match('/^(.*)\.cow$/', $cow_file, $matches)) {
          continue;
        }
        $cow_name = $matches[1];
        $cow_name = phutil_utf8_strtolower($cow_name);

        $joke = $jokes[$j % count($jokes)];

        $content .= "\n=== ".$cow_name;
        $content .= "\n```";
        $content .= "\ncowsay(cow='".$cow_name."'){{{{$joke}}}}";
        $content .= "\n```";
        $content .= "\ncowsay(cow='".$cow_name."'){{{{$joke}}}}";
        $content .= "\n";

        $j++;
      }
    }

    $content .= "\n";
    $content .= '== Parameters';

    $joke = $jokes[$j % count($jokes)];
    $j++;

    $content .= "\n=== eyes";
    $content .= "\n```";
    $content .= "\ncowsay(cow='cow', eyes='-x'){{{{$joke}}}}";
    $content .= "\n```";
    $content .= "\ncowsay(cow='cow', eyes='-x'){{{{$joke}}}}";
    $content .= "\n";

    $joke = $jokes[$j % count($jokes)];
    $j++;

    $content .= "\n=== think";
    $content .= "\n```";
    $content .= "\ncowsay(cow='cow', think=1){{{{$joke}}}}";
    $content .= "\n```";
    $content .= "\ncowsay(cow='cow', think=1){{{{$joke}}}}";
    $content .= "\n";

    $joke = $jokes[$j % count($jokes)];
    $j++;

    $content .= "\n=== tongue";
    $content .= "\n```";
    $content .= "\ncowsay(cow='cow', tongue=U){{{{$joke}}}}";
    $content .= "\n```";
    $content .= "\ncowsay(cow='cow', tongue=U){{{{$joke}}}}";
    $content .= "\n";

    return $content;
  }
}
