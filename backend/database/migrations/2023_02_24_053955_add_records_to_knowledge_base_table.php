<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Add records to knowledge_base table
        $knowledge_base = array(
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Is it possible to validate a European patent in Türkiye?','question_link' => 'is-it-possible-to-validate-a-european-patent-in-turkiye','answer' => 'Yes, Türkiye is a contracting state to the European Patent Convention and the validation is a mandatory step for owning an enforceable right in Türkiye in relation to a European patent after the grant before the EPO. It is also the case that EP validations are very common in Türkiye such that roughly one third of all patent grants in a year in Türkiye are EP validations.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Must a national professional representative be appointed for European patent validations in Türkiye?','question_link' => 'must-a-national-professional-representative-be-appointed-for-european-patent-validations-in-turkiye','answer' => 'Yes, if the applicant does not have a Turkish residence or head office located in Türkiye, you must appoint a registered Turkish patent attorney for European patent validations in Türkiye. Moreover, even if you satisfy residence and/or place of business requirements, we still advise you to work with a national patent attorney due to better communication prospects with the patent office.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Is Türkiye a contracting state to the London Agreement?','question_link' => 'is-turkiye-a-contracting-state-to-the-london-agreement','answer' => 'No, Türkiye is not a contracting state to the London Agreement. Therefore, a translation into Turkish of the complete patent specification is required.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Is the translation of the description required for European patent validations in Türkiye?','question_link' => 'is-the-translation-of-the-description-required-for-european-patent-validations-in-turkiye','answer' => 'Yes.Turkpatent requires full specification, including claims, drawings and description, to be translated into Turkish. You can use our <a href="/european-patent-validation-in-turkey">EP Validation tool</a> to get a quick quote on the final translation fees to be incurred on your validation.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'What is the period for filing the translation of a European patent validation in Türkiye?','question_link' => 'what-is-the-period-for-filing-the-translation-of-a-european-patent-validation-in-turkiye','answer' => 'The translation is required to be filed within three months after the date on which the mention of the grant is published in the European Patent Bulletin. An extension of three months by paying a surcharge is also possible.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Is late validation of a European patent possible?','question_link' => 'is-late-validation-of-a-european-patent-possible','answer' => 'A three-month extension for filing the translation is available by paying a surcharge. But the option of filing the translation during this three-month extension is available only if the fee for publication and the surcharge have been paid <b> in advance</b>, within the initial period of three months from the mention of grant. If your case does not meet these conditions, there might be some edge cases where it may be still possible to validate your patent. In case you need further information on the matter please <a href="/contact?=Late-EP-Application">contact us</a>.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'How much does a validation of a European patent cost in Türkiye?','question_link' => 'how-much-does-a-validation-of-a-european-patent-cost-in-turkiye','answer' => 'Costs of validation of a European patent in Türkiye consists of official fees, representative fees and translation fees. Official fees for filing a European patent validation may change significantly in each year and you can review the most recent official fees in <a href="knowledge-base/european-patent-validation-in-turkiye/what-are-the-official-fees-for-european-patent-validations-in-turkiye">this page</a>. <br> Total cost of a validation varies from patent to patent. You can use our EP Validation tool <a href="/european-patent-validation-in-turkiye"> for an instant accurate quote for your European patent validation in Türkiye.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'What are the official fees for European patent validations in Türkiye?','question_link' => 'what-are-the-official-fees-for-european-patent-validations-in-turkiye','answer' => 'The official fee is TRY 8910.00 in 2023 for publication of a translation of the patent specification. But for a three-month extension for late filing of the translation the payment of a surcharge of TRY 5040.00 is required in advance. <br> You can use our <a href=\'/european-patent-validation-in-turkiye\'>EP Validation tool</a> to get an accurate calculation of both the official fees and the total costs for the validation of your European patents in Türkiye.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'When are the official fees due?','question_link' => 'when-are-the-official-fees-due','answer' => 'The official fees are due within three months after the date on which the mention of the grant is published in the European Patent Bulletin. If you are requesting a three-month extension for late filing, the surcharge needs to be paid in advance within the same period.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'How long does it take to validate a European patent in Türkiye?','question_link' => 'how-long-does-it-take-to-validate-a-european-patent-in-turkiye','answer' => 'Validation of a European patent in Türkiye is usually completed within days. Nevertheless, the translation process has some natural variability built into it. To be on the safe side and not to incur additional surcharges, timely instructions are advised.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Is power of attorney required to validate a European patent in Türkiye?','question_link' => 'is-power-of-attorney-required-to-validate-a-european-patent-in-turkiye','answer' => 'No, power of attorney is not required in Türkiye for European Patent validations. However, your attorney still might prefer to have an executed PoA from the applicant in case for future requirements.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Is late filing of the translation possible?','question_link' => 'is-late-filing-of-the-translation-possible','answer' => 'Yes,  a three-month extension is available by paying a surcharge. But the option of filing the translation during this three-month extension is available only if the fee for publication and the surcharge have been paid <b>in advance</b>, within the initial period of three months from the mention of grant.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'Is correction of translation permitted?','question_link' => 'is-correction-of-translation-permitted','answer' => 'Yes, the correction of translations are allowed in Türkiye. A corrected translation of the patent specification may be filed anytime after validation of the European Patent. You can review the most recent official fees for filing a corrected translation here <a href=\'knowledge-base/european-patent-validation-in-turkiye/is-power-of-attorney-required-to-validate-a-european-patent-in-turkiye\'></a>\' .','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),
            array('title' => 'European Patent Validation In Turkiye','slug' => 'european-patent-validation-in-turkiye','question' => 'What are the official fees payable in filing a corrected translation?','question_link' => 'what-are-the-official-fees-payable-in-filing-a-corrected-translation','answer' => 'If you are in need of a correction of translation, the official fee for the publication of a corrected translation of the patent specification is TRY 2820.00 during the year of 2023.','type' => 'Knowledge Base','created_at' => NULL,'updated_at' => NULL),




        );

        DB::table('knowledge_base')->insert($knowledge_base);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_base', function (Blueprint $table) {
            //
        });
    }
};
