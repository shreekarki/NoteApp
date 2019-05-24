<template>
    <div class="col-md-3 nopadding">
        <div class="note-container">
            <div class="note-title">Real Notes</div>
            <div class="notes-items" v-for="(note,index) in notes" @click="selectNotes(note)">
                {{note.title}}
                <span @click="remove(note,index)">remove</span>
            </div>
            <div class="add-note" @click="createNote">New Note?</div>
        </div>
    </div>
    <div class="col-md-12"><span class="user-logged" style="cursor:pointer" @click="Logout">Logout</span></div>
</template>
<script>
    export default{
        props:['initialnotes'],
        data: function(){
            return {
                notes:this.initialnotes
            }
        },
        methods:{
            selectNotes:function(note){
                this.$emit('noteChosen',note);
            },
            remove: function(note,index){
                axios.delete('api/notes/delete/'+note.id).then((res)=>{
                    console.log(res.data);
                    this.notes.splice(index,1);
                })
            },
            createNote:function(){
                axios.post('/api/notes/create', {
                    title:'What\' on your mind',
                    content:'Say something Nice'
                }).then((res) => {
                    this.notes.push({
                        title:'What\' on your mind',
                        content:'Say something Nice'
                    });
                })
            }
        },
        Logout:function () {
            axios.post('logout').then(response => {
                location.reload();
            }).catch(error => {
                location.reload();
            })
        },
    }
</script>
