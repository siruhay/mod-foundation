<template>
	<form-show with-helpdesk>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Name"
							v-model="record.name"
							readonly
						></v-text-field>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:info="{ theme }">
			<div class="text-overline mt-4">Link</div>
			<v-divider class="mb-3"></v-divider>

			<v-row dense>
				<v-col cols="6">
					<v-btn
						:color="theme"
						variant="flat"
						block
						@click="
							$router.push({
								name: 'foundation-communitypos',
							})
						"
						>jabatan</v-btn
					>
				</v-col>

				<v-col cols="6">
					<v-btn
						:color="theme"
						variant="flat"
						block
						@click="
							$router.push({
								name: 'foundation-member',
							})
						"
						>anggota</v-btn
					>
				</v-col>
			</v-row>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "foundation-community-show",
};
</script>
