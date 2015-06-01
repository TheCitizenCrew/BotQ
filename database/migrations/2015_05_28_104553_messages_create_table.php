<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MessagesCreateTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onDelete('cascade');
            $table->string('label', \App\Models\Message::LABEL_LENGTH);
            // priorité entre messages
            $table->smallInteger('priority');
            // quand priorité supérieure, que faire de l'action précédente ?
            // pause ou stop
            $table->enum('concurentAction', ['pause', 'stop']);
            // si aucune action à suivre, stop ou en boucle ?
            $table->boolean('playloop');
            $table->string('content_type');
            $table->text('content');
            $table->timestamp('status_got');
            $table->timestamp('status_done');
            $table->timestamp('status_aborted');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
