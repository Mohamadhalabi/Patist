<?php

use App\Models\Knowledgebase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Contents for Renewal Knowledge Base
        Schema::table('knowledge_base', function (Blueprint $table) {
            $contents = array(
                array(
                    'title' => 'Automate your Turkish patent renewals',
                    'slug' => 'automate-your-turkish-patent-renewals',
                    'answer' => '<p>Turkish patents and utility models must be renewed annually at the Turkish Patent and Trademark Office (T&Uuml;RKPATENT). Renewal actions extend the protection period of your patents and utility models.</p>
                    <p>Patent.Istanbul is a service designed to make the process of renewing your Turkish patents and utility models easier. Our service allows you to add your patents to our system and calculate the renewal fees automatically.</p>
                    <p>The advantages of using Patent.Istanbul to automate your Turkish patent renewal process include:</p>
                    <ul>
                        <li><strong>Time savings:</strong> With Patent.Istanbul, the renewal processes are easy and you do not have to manage the renewal process anymore for the rest of the life of the patent.</li>
                        <li><strong>Money savings:</strong> Patent.Istanbul is automated and it is a low-cost solution for annuities. Renewal of the patents annually is a procedural requirement and best accomodated by good help of automation.</li>
                        <li><strong>Security:</strong> Patent.Istanbul is designed to manage the renewal process for your patents in a secure way. We have multiple guards to ensure that the orders are taken and executed without errors, in a timely and safe manner.</li>
                        <li><strong>Reminders:</strong> Patent.Istanbul also takes care of the reminders for future renewal actions, if you want it to. You can reliably delegate all renewals and recieve notifications only when needed.</li>
                    </ul>
                    <p>To learn more about how you can benefit from Patent.Istanbul, please contact us.</p>',
                ),
                array(
                    'title' => 'Keep Your Patents Up to Date with Turkish Patent Renewal Services',
                    'slug' => 'keep-your-patents-up-to-date-with-turkish-patent-renewal-services',
                    'answer' => '<p>Keeping patents up to date involves tracking the calendar, managing communications with the patent office and the patent firm, handling payments, making requests on behalf of the patent office and making sure the process finalizes without an error. This process can be cumbersome or trivial for some and it best that it is being handled by a dedicated system. Patent Istanbul makes this process simple and hassle-free for you, and at a lower cost.</p>
                    <p>Adding your patents to our system and initiating automation is simple. You will know the renewal costs for your patents in advance. There are no hidden costs to worry about. It is also a good tool to plan your budget effectively.</p>
                    <p>Additionally, if you would like to share your patent portfolio with us, you can do so easily in bulk. Our team can add your portfolio to your account free of charge, and we also provide an API for integrating further automation into your software for IP services in T&uuml;rkiye.</p>',
                ),
                array(
                    'title' => 'What Is the Turkish Patent and Utility Model Renewal Process and Why Is It Important?',
                    'slug' => 'what-is-the-turkish-patent-and-utility-model-renewal-process-and-why-is-it-important',
                    'answer' => '<p>Obtaining a patent or a utility model in T&uuml;rkiye is a significant way to protect your inventions. After aquiring them and getting a monopoly on the invention; you must also renew your patents annually.</p>
                    <p>Turkish Patent and Trademark Office (T&Uuml;RKPATENT) has mandated the annual renewal of patents and utility models. The purpose of this process is to ensure that patent holders keep their inventions current and protected. If you fail to renew your patent, your protection rights expire, and others can use or imitate your invention.</p>
                    <p>The Turkish Patent and Utility Model renewal process involves making payments and submitting necessary documents by a specific deadline. At Patent Istanbul, we offer a service that automates the renewal of patents and utility models. You can streamline this process, making it simple, fast, and reliable. Our system automatically calculates renewal costs and sends you reminders for renewals, allowing you to manage the process seamlessly. This way, you can focus on your business and rest assured that your inventions are protected.</p>',
                ),
                array(
                    'title' => 'Frequently Asked Questions and Answers About the Turkish Patent Renewal Process',
                    'slug' => 'frequently-asked-questions-and-answers-about-the-turkish-patent-renewal-process',
                    'answer' => '<p>Please see below the frequently asked questions about Turkish Patent renewals:</p>
                    <p><strong>Question 1: When should I renew my patent?</strong></p>
                    <p>Patents and utility models must be renewed yearly, before their protection periods expire. The timing of renewal is specific to your patent and calculated based on when you apply for the patent or utility model.</p>
                    <p><strong>Question 2: How long does the patent renewal process take?</strong></p>
                    <p>A patent renewal process typically involves getting reminders, handling instructions and payments and the renewal itself at T&Uuml;RKPATENT. The renewal itself does not take long, it is usually processed within the day of request, given the application is made properly. The remaining parts, on the other hand, can take days, depending on how many people and decision makers are involved and how busy they are, it can take weeks.&nbsp;</p>
                    <p>For this reason, Patent.Istanbul sends reminders and works out the process in a timely manner.</p>
                    <p><strong>Question 3: What are the patent renewal fees?</strong></p>
                    <p>Patent renewal fees vary based on the type of patent, its duration, the renewal timing and services fees. Patent.Istanbul automatically calculate and provide these fees for you.</p>
                    <p><strong>Question 4: What happens if I miss a renewal?</strong></p>
                    <p>If you fail to renew your patent or utility model, the protection of the intellectual property expires and you no longer get the benefits of the patent enforcements.</p>
                    <p>However, if you miss a renewal due date, you get a small time window in which you can make a late renewal. In this case, you should go on with the renewal as soon as possible not to miss this window as well. Patent.Istanbul handles late renewals as well, provided that you contacted us in a timely manner.</p>',
                ),
                array(
                    'title' => 'The Value of Patents and Innovation for a Business',
                    'slug' => 'the-value-of-patents-and-innovation-for-a-business',
                    'answer' => '<p>In today&#39;s business world, it&#39;s not enough for businesses to achieve short-term successes. Sustainable growth and competitive advantage require innovation and intellectual property to play a significant role. In this context, placing patents at the core of a business strategy and consistently renewing them holds great value for a business.</p>
                    <p>The Value of Patents and Innovation for a Business</p>
                    <p><strong>Product and Service Development:</strong> Patents encourage the development of new products or services. Innovation can help you provide better products to customers, expanding your market.</p>
                    <p><strong>Competitive Advantage:</strong> A robust patent portfolio provides a competitive edge against your competitors. It makes it easier for you to offer unique products or processes.</p>
                    <p><strong>Value Enhancement:</strong> Patents can increase the value of your business and attract potential investors or business partners. It demonstrates the long-term growth potential of your business.</p>
                    <p><strong>Legal Protection:</strong> Patents protect your intellectual property and prevent others from infringing on it. Legal protection safeguards your business from risks.</p>
                    <p><strong>Marketing and Brand Development:</strong> Patents can help you gain the trust of customers and business partners. Being a patent holder can enhance your credibility.</p>
                    <p>To maximize the growth potential of your business and stay ahead of the competition, it&#39;s important to view patents and innovation as a fundamental part of your business strategy. The Turkish Patent and Utility Model Renewal Process is one way to support and protect this strategy. Managing this process can be made easier by leveraging the expertise of a service like Patent Istanbul.</p>',
                ),
                array(
                    'title' => 'Common Mistakes in the Turkish Patent Renewal Process and How to Avoid Them',
                    'slug' => 'common-mistakes-in-the-turkish-patent-renewal-process-and-how-to-avoid-them',
                    'answer' => '<p>Here are some common mistakes made during the Turkish Patent Renewal Process and tips on how to avoid them:</p>
                    <p><strong>Mistake 1: Forgetting the Renewal Deadline</strong></p>
                    <p>Many businesses may forget the renewal deadline for their patents or utility models, risking the loss of their rights. Solution: Use a reminder system or rely on a professional service to track the deadline.</p>
                    <p><strong>Mistake 2: Submitting Incorrect or Incomplete Documents</strong></p>
                    <p>The Turkish Patent Renewal Process requires document submission. Submitting incorrect or incomplete documents can prolong the process. Solution: Prepare the documents accurately and on time or seek assistance from an expert.</p>
                    <p><strong>Mistake 3: Incorrect Calculation of Renewal Fees</strong></p>
                    <p>Renewal fees for patents can vary based on the type and duration of the patent. Incorrect fee calculations can lead to financial issues. Solution: Use a fee calculation tool or professional service to accurately calculate fees.</p>
                    <p><strong>Mistake 4: Not Seeking Expert Consultation</strong></p>
                    <p>The Turkish Patent Renewal Process can be complex, and missteps can have serious consequences. Seeking expert consultation can help avoid these mistakes. Solution: Seek guidance from patent experts or services.</p>
                    <p><strong>Mistake 5: Neglecting the Process</strong></p>
                    <p>The Turkish Patent Renewal Process can be an overlooked aspect of business operations. However, neglecting it can jeopardize intellectual property rights. Solution: Take the process seriously and take the necessary steps in a timely manner.</p>',
                ),
            );
            foreach ($contents as $content) {
                $kb = new Knowledgebase();
                $kb->title = $content['title'];
                $kb->slug = $content['slug'];
                $kb->question = $content['title'];
                $kb->question_link = $content['slug'];
                $kb->answer = $content['answer'];
                $kb->type = 'Knowledge Base';
                $kb->project = 'renewal';
                $kb->created_at = now();
                $kb->updated_at = now();
                $kb->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_base', function (Blueprint $table) {

        });
    }
};
