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
            $table->smallInteger('priority')->default(0);
            ;
            // quand priorité supérieure, que faire de l'action précédente ?
            // pause ou stop
            $table->enum('priority_action', [
                'pause',
                'stop',
                'simult'
            ])->default('pause');
            // si aucune action à suivre, stop ou en boucle ?
            $table->boolean('play_loop')->default(false);
            $table->time('play_at_time')->nullable();
            $table->string('content_type');
            $table->text('content');
            $table->timestamp('status_got')->nullable();
            $table->timestamp('status_done')->nullable();
            $table->timestamp('status_aborted')->nullable();
            
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
