<div class="form-control">
    <label class="label cursor-pointer">
        <span class="label-text">Enable Order Confirmations</span>
        <input type="checkbox" name="has_confirmations" class="toggle toggle-primary" value="1" 
            {{ old('has_confirmations', $project->has_confirmations ?? false) ? 'checked' : '' }}>
    </label>
    <p class="text-sm text-base-content/70 mt-1">
        Allow project members to create and manage order confirmations for this project.
    </p>
</div> 