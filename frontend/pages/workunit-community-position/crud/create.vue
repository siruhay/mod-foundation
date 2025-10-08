<template>
	<form-create with-helpdesk>
		<template v-slot:default="{ combos: { parents, posmaps }, record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-select
							:items="posmaps"
							label="Name"
							v-model="record.posmap_id"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="parents"
							label="Parent"
							v-model="record.parent_id"
							hide-details
						></v-select>
					</v-col>
				</v-row>
			</v-card-text>
		</template>
	</form-create>
</template>

<script>
export default {
	name: "foundation-position-create",
};
</script>
