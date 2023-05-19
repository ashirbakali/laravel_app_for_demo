<template>
    <tr :data="itemData" >
        <td data-label="Item">
            <v-select v-model="itemData.item" @input="itemSelect($event)" name="items" class="my-select"
                      :options="items.map((item,itemIndex)=>({label:item.name,code:item.id,itemIndex}))"></v-select>
        </td>
        <td data-label="Cost"><input  v-model="itemData.cost" name="cost" type="number" class="form-control" :readonly="!itemData.item"></td>

        <td class="stock-td"  data-label="Stock">{{ itemData.stock }}</td>
        <td  data-label="Qty"><input v-model="itemData.qty" name="qty" type="number" class="form-control" value="0" :readonly="!itemData.item"></td>
        <td data-label="Total">{{  $root.price(itemData.total) }}</td>
        <td class="trash-td"><i v-on:click="$emit('onDelete',index)" style="color:red;cursor: pointer" class="fas fa-trash"></i></td>

    </tr>
</template>

<script>

export default {

    name: "PoItem",
    props: ['items', 'index', 'po'],
    data() {
        return {
            itemData:{...this.po}
        }
    },
    methods: {
        itemSelect(e) {
            if (e) {
                const {itemIndex} = e;
                this.itemData.cost = 0
                this.itemData.stock = this.items[itemIndex].inventory_qty


            } else {
                this.itemData.cost = 0
                this.itemData.stock = 0
            }
        },


    },
    watch: {
        itemData:{
            handler(newVal,oldVal){

                newVal.total = newVal.cost*newVal.qty
/*                if(parseInt(newVal.stock) < parseInt(newVal.qty)){
                    newVal.qty = newVal.stock;
                }*/
                this.$emit("change",this.index, newVal)
            },
            deep: true
        }
    }, mounted: function () {
        console.log(this.itemData)
        console.log(this.items)
    }
}
</script>
<style>
.my-select {
    background: white;
}

td {
    vertical-align: inherit !important;
}

.stock-td,.trash-td {
    text-align: center !important;
}
</style>
